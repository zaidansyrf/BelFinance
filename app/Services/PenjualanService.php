<?php

namespace App\Services;

use App\Models\Income;
use App\Models\IncomeDetail;
use App\Models\Item;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * Menyimpan penjualan (pembayaran pesanan). Dipakai oleh form Pembayaran
 * admin keuangan dan halaman POS kasir.
 */
class PenjualanService
{
    /**
     * Hitung subtotal dan total dari daftar item. Harga selalu diambil dari database,
     * bukan dari input, supaya tidak bisa dimanipulasi dari browser.
     *
     * Mendukung diskon per-item (item_discount_type & item_discount_value opsional).
     *
     * @param  array<int, array{item_id: int|string, quantity: int|string, item_discount_type?: string|null, item_discount_value?: float|null}>  $items
     * @return array{total: int, details: array<int, array{item: Item, quantity: int, raw_total: int, subtotal: int, item_discount_type: string|null, item_discount_value: float|null}>}
     */
    public function hitung(array $items): array
    {
        $itemMap = Item::whereIn('id', collect($items)->pluck('item_id'))->get()->keyBy('id');

        $details = [];
        $total = 0;
        foreach ($items as $row) {
            $item = $itemMap->get($row['item_id']);
            if (! $item) {
                continue;
            }

            $quantity  = (int) $row['quantity'];
            $rawTotal  = $item->price * $quantity;

            // --- Hitung diskon item ---
            $discType  = $row['item_discount_type']  ?? null;
            $discValue = isset($row['item_discount_value']) && $row['item_discount_value'] !== ''
                ? (float) $row['item_discount_value']
                : null;

            $diskonNominal = 0;
            if ($discType && $discValue) {
                if ($discType === 'percent') {
                    $diskonNominal = (int) round(($discValue / 100) * $item->price);
                } else {
                    $diskonNominal = (int) $discValue;
                }
            }

            $subtotal = $rawTotal - ($diskonNominal * $quantity);
            $subtotal = max(0, $subtotal); // tidak boleh negatif
            $total   += $subtotal;

            $details[] = [
                'item'               => $item,
                'quantity'           => $quantity,
                'raw_total'          => $rawTotal,
                'item_discount_type' => $discType,
                'item_discount_value'=> $discValue,
                'subtotal'           => $subtotal,
            ];
        }

        return ['total' => $total, 'details' => $details];
    }

    /**
     * @param  array{
     *   name: string,
     *   source_id: int|string,
     *   shift_id?: int|null,
     *   items: array,
     *   paid_amount?: int|string|null,
     *   description?: string|null,
     *   date?: string|null,
     *   order_source?: string|null,
     *   app_discount_type?: string|null,
     *   app_discount_value?: float|null,
     *   is_app_subsidy?: bool|null,
     * }  $data
     *
     * @throws ValidationException jika uang diterima kurang dari total
     */
    public function simpan(array $data): Income
    {
        ['total' => $grossTotal, 'details' => $details] = $this->hitung($data['items']);

        // --- Hitung promo aplikasi ---
        $appDiscType  = $data['app_discount_type']  ?? null;
        $appDiscValue = isset($data['app_discount_value']) && $data['app_discount_value'] !== ''
            ? (float) $data['app_discount_value']
            : null;
        $isSubsidy    = ! empty($data['is_app_subsidy']);

        $appDiscNominal = 0;
        if ($appDiscType && $appDiscValue) {
            if ($appDiscType === 'percent') {
                $appDiscNominal = (int) round(($appDiscValue / 100) * $grossTotal);
            } else {
                $appDiscNominal = (int) $appDiscValue;
            }
        }

        $netTotal = max(0, $grossTotal - $appDiscNominal);

        // --- amount = omset yang diakui ---
        // Skenario A (subsidi aplikasi): omset diakui penuh = gross_total
        // Skenario B (merchant tanggung): omset = net_total
        $amount = $isSubsidy ? $grossTotal : $netTotal;

        $paid = isset($data['paid_amount']) && $data['paid_amount'] !== '' ? (int) $data['paid_amount'] : null;
        if ($paid !== null && $paid < $netTotal) {
            $rp = fn (int $nilai) => 'Rp '.number_format($nilai, 0, ',', '.');

            throw ValidationException::withMessages([
                'paid_amount' => "Uang kurang {$rp($netTotal - $paid)}. Total tagihan {$rp($netTotal)}, uang diterima {$rp($paid)}.",
            ]);
        }

        return DB::transaction(function () use ($data, $amount, $grossTotal, $netTotal, $appDiscType, $appDiscValue, $isSubsidy, $details, $paid) {
            $this->kurangiStok($details);

            $income = Income::create([
                'shift_id'          => $data['shift_id'] ?? null,
                'name'              => $data['name'],
                'order_source'      => $data['order_source'] ?? null,
                'amount'            => $amount,
                'gross_total'       => $grossTotal,
                'app_discount_type' => $appDiscType,
                'app_discount_value'=> $appDiscValue,
                'is_app_subsidy'    => $isSubsidy,
                'net_total'         => $netTotal,
                'paid_amount'       => $paid,
                'change_amount'     => $paid !== null ? $paid - $netTotal : null,
                'date'              => ! empty($data['date']) ? $data['date'] : now(),
                'description'       => $data['description'] ?? 'Pesanan baru',
                'source_id'         => $data['source_id'],
                'type'              => 'payment',
            ]);

            foreach ($details as $detail) {
                IncomeDetail::create([
                    'income_id'          => $income->id,
                    'item_id'            => $detail['item']->id,
                    'quantity'           => $detail['quantity'],
                    'item_discount_type' => $detail['item_discount_type'],
                    'item_discount_value'=> $detail['item_discount_value'],
                    'subtotal'           => $detail['subtotal'],
                ]);

                // Tambahkan jumlah terjual menu
                $detail['item']->increment('quantity', $detail['quantity']);
            }

            return $income;
        });
    }

    /**
     * Kurangi stok menu yang dilacak. Baris dikunci agar dua kasir tidak menjual stok yang sama.
     *
     * @param  array<int, array{item: Item, quantity: int, subtotal: int}>  $details
     *
     * @throws ValidationException jika stok tidak cukup
     */
    private function kurangiStok(array $details): void
    {
        $kurang = [];
        foreach ($details as $detail) {
            $item = Item::lockForUpdate()->find($detail['item']->id);
            if (! $item->lacakStok()) {
                continue;
            }

            if ($item->stock < $detail['quantity']) {
                $kurang[] = "{$item->name} (sisa {$item->stock}, diminta {$detail['quantity']})";

                continue;
            }

            $item->decrement('stock', $detail['quantity']);
        }

        if ($kurang) {
            throw ValidationException::withMessages([
                'items' => 'Stok tidak cukup: '.implode('; ', $kurang).'.',
            ]);
        }
    }
}
