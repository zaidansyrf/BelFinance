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
     * @param  array<int, array{item_id: int|string, quantity: int|string}>  $items
     * @return array{total: int, details: array<int, array{item: Item, quantity: int, subtotal: int}>}
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

            $quantity = (int) $row['quantity'];
            $subtotal = $item->price * $quantity;
            $total += $subtotal;

            $details[] = ['item' => $item, 'quantity' => $quantity, 'subtotal' => $subtotal];
        }

        return ['total' => $total, 'details' => $details];
    }

    /**
     * @param  array{name: string, source_id: int|string, items: array, paid_amount?: int|string|null, description?: string|null}  $data
     *
     * @throws ValidationException jika uang diterima kurang dari total
     */
    public function simpan(array $data): Income
    {
        ['total' => $total, 'details' => $details] = $this->hitung($data['items']);

        $paid = isset($data['paid_amount']) && $data['paid_amount'] !== '' ? (int) $data['paid_amount'] : null;
        if ($paid !== null && $paid < $total) {
            throw ValidationException::withMessages([
                'paid_amount' => 'Uang diterima kurang dari total belanja (Rp '.number_format($total, 0, ',', '.').').',
            ]);
        }

        return DB::transaction(function () use ($data, $total, $details, $paid) {
            $income = Income::create([
                'name' => $data['name'],
                'amount' => $total,
                'paid_amount' => $paid,
                'change_amount' => $paid !== null ? $paid - $total : null,
                'date' => now(),
                'description' => $data['description'] ?? 'Pesanan baru',
                'source_id' => $data['source_id'],
                'type' => 'payment',
            ]);

            foreach ($details as $detail) {
                IncomeDetail::create([
                    'income_id' => $income->id,
                    'item_id' => $detail['item']->id,
                    'quantity' => $detail['quantity'],
                    'subtotal' => $detail['subtotal'],
                ]);

                // Tambahkan jumlah terjual menu
                $detail['item']->increment('quantity', $detail['quantity']);
            }

            return $income;
        });
    }
}
