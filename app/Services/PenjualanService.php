<?php

namespace App\Services;

use App\Models\Income;
use App\Models\Item;
use App\Models\PlatformPromo;
use App\Models\Source;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * Menyimpan penjualan (pembayaran pesanan).
 * Menghitung snapshot finansial promo menu merchant, promo platform, komisi, dan net payout.
 * Dipakai oleh form Pembayaran admin keuangan dan halaman POS kasir.
 */
class PenjualanService
{
    /**
     * Hitung rincian finansial pesanan dari daftar item, sumber pesanan, dan preset promo platform.
     *
     * @param  array<int, array{item_id: int|string, quantity: int|string, apply_item_promo?: bool|null}>  $itemsData
     * @return array{
     *   gross_amount: int,
     *   item_discount_total: int,
     *   subtotal_after_item_promo: int,
     *   platform_promo_total: int,
     *   platform_merchant_share: int,
     *   platform_app_subsidy: int,
     *   commission_percent: float,
     *   commission_amount: int,
     *   customer_paid_amount: int,
     *   net_payout: int,
     *   details: array<int, array{
     *     item: Item,
     *     item_promo_id: int|null,
     *     quantity: int,
     *     original_price: int,
     *     discount_amount: int,
     *     final_price: int,
     *     subtotal: int
     *   }>
     * }
     */
    public function hitung(array $itemsData, ?Source $source = null, ?PlatformPromo $platformPromo = null): array
    {
        $itemIds = collect($itemsData)->pluck('item_id')->filter()->unique()->all();
        $itemsMap = Item::with(['promo', 'promos'])->whereIn('id', $itemIds)->get()->keyBy('id');

        $grossAmount = 0;
        $itemDiscountTotal = 0;
        $details = [];

        foreach ($itemsData as $row) {
            /** @var Item|null $item */
            $item = $itemsMap->get($row['item_id']);
            if (! $item) {
                continue;
            }

            $quantity = max(1, (int) $row['quantity']);
            $originalPrice = (int) $item->price;

            // Default true: promo menu otomatis terpasang kecuali dilepas manual (override)
            $applyPromo = array_key_exists('apply_item_promo', $row)
                ? (bool) $row['apply_item_promo']
                : true;

            $unitDiscount = 0;
            $appliedPromoId = null;

            if ($applyPromo) {
                $isOnline = $source ? (bool) $source->is_online : false;
                $promo = $item->activePromoFor($isOnline);
                if (! $promo && $item->promo && $item->promo->isEligibleForSource($source)) {
                    $promo = $item->promo;
                }
                if ($promo) {
                    $appliedPromoId = $promo->id;
                    $unitDiscount = $promo->calculateUnitDiscount($originalPrice);
                }
            }

            $finalPrice = max(0, $originalPrice - $unitDiscount);
            $subtotal = $finalPrice * $quantity;

            $grossAmount += ($originalPrice * $quantity);
            $itemDiscountTotal += ($unitDiscount * $quantity);

            $details[] = [
                'item'            => $item,
                'item_promo_id'   => $appliedPromoId,
                'quantity'        => $quantity,
                'original_price'  => $originalPrice,
                'discount_amount' => $unitDiscount,
                'final_price'     => $finalPrice,
                'subtotal'        => $subtotal,
            ];
        }

        // Subtotal setelah promo menu merchant
        $subtotalAfterItemPromo = max(0, $grossAmount - $itemDiscountTotal);

        // Breakdown promo platform
        $platformBreakdown = $platformPromo
            ? $platformPromo->calculateBreakdown($subtotalAfterItemPromo)
            : ['total' => 0, 'merchant_share' => 0, 'app_subsidy' => 0];

        // Komisi platform
        $commissionPercent = $source ? (float) $source->default_commission_percent : 0.0;
        $commissionAmount = (int) round($subtotalAfterItemPromo * ($commissionPercent / 100));

        $customerPaidAmount = max(0, $subtotalAfterItemPromo - $platformBreakdown['total']);
        $netPayout = max(0, $subtotalAfterItemPromo - $platformBreakdown['merchant_share'] - $commissionAmount);

        return [
            'gross_amount'              => $grossAmount,
            'item_discount_total'       => $itemDiscountTotal,
            'subtotal_after_item_promo' => $subtotalAfterItemPromo,
            'platform_promo_total'      => $platformBreakdown['total'],
            'platform_merchant_share'   => $platformBreakdown['merchant_share'],
            'platform_app_subsidy'      => $platformBreakdown['app_subsidy'],
            'commission_percent'        => $commissionPercent,
            'commission_amount'         => $commissionAmount,
            'customer_paid_amount'      => $customerPaidAmount,
            'net_payout'                => $netPayout,
            'details'                   => $details,
        ];
    }

    /**
     * Simpan transaksi penjualan ke database.
     *
     * @param  array{
     *   name: string,
     *   source_id: int|string,
     *   items: array,
     *   shift_id?: int|null,
     *   platform_promo_id?: int|string|null,
     *   paid_amount?: int|string|null,
     *   change_amount?: int|string|null,
     *   date?: string|null,
     *   description?: string|null,
     * }  $data
     *
     * @throws ValidationException
     */
    public function simpan(array $data): Income
    {
        $source = Source::findOrFail($data['source_id']);

        $platformPromo = null;
        if (! empty($data['platform_promo_id'])) {
            $platformPromo = PlatformPromo::where('id', $data['platform_promo_id'])
                ->where('source_id', $source->id)
                ->where('is_active', true)
                ->first();

            if (! $platformPromo) {
                throw ValidationException::withMessages([
                    'platform_promo_id' => 'Preset promo platform tidak valid atau sudah tidak aktif.',
                ]);
            }
        }

        $calc = $this->hitung($data['items'], $source, $platformPromo);

        $customerPaidAmount = $calc['customer_paid_amount'];
        $paid = isset($data['paid_amount']) && $data['paid_amount'] !== '' ? (int) $data['paid_amount'] : null;

        // Validasi uang diterima jika diisi dan kurang dari tagihan pelanggan
        if ($paid !== null && $paid < $customerPaidAmount) {
            $rp = fn (int $v) => 'Rp ' . number_format($v, 0, ',', '.');
            throw ValidationException::withMessages([
                'paid_amount' => "Uang kurang {$rp($customerPaidAmount - $paid)}. Total belanja {$rp($customerPaidAmount)}, uang diterima {$rp($paid)}.",
            ]);
        }

        $change = null;
        if ($paid !== null) {
            $change = isset($data['change_amount']) && $data['change_amount'] !== ''
                ? (int) $data['change_amount']
                : max(0, $paid - $customerPaidAmount);
        }

        return DB::transaction(function () use ($data, $source, $platformPromo, $calc, $paid, $change) {
            // Kunci baris menu & update stok
            $this->prosesStokDanPenjualan($calc['details']);

            $income = Income::create([
                'source_id'               => $source->id,
                'shift_id'                => $data['shift_id'] ?? null,
                'platform_promo_id'       => $platformPromo?->id,
                'name'                    => $data['name'],
                'gross_amount'            => $calc['gross_amount'],
                'item_discount_total'     => $calc['item_discount_total'],
                'platform_promo_total'    => $calc['platform_promo_total'],
                'platform_merchant_share' => $calc['platform_merchant_share'],
                'platform_app_subsidy'    => $calc['platform_app_subsidy'],
                'commission_percent'      => $calc['commission_percent'],
                'commission_amount'       => $calc['commission_amount'],
                'amount'                  => $calc['net_payout'], // Net payout uang bersih masuk
                'customer_paid_amount'    => $calc['customer_paid_amount'],
                'paid_amount'             => $paid,
                'change_amount'           => $change,
                'date'                    => ! empty($data['date']) ? $data['date'] : now()->toDateString(),
                'description'             => $data['description'] ?? 'Pesanan baru',
                'type'                    => 'payment',
            ]);

            foreach ($calc['details'] as $d) {
                $income->incomeDetails()->create([
                    'item_id'         => $d['item']->id,
                    'item_promo_id'   => $d['item_promo_id'],
                    'quantity'        => $d['quantity'],
                    'original_price'  => $d['original_price'],
                    'discount_amount' => $d['discount_amount'],
                    'final_price'     => $d['final_price'],
                    'subtotal'        => $d['subtotal'],
                ]);
            }

            return $income;
        });
    }

    /**
     * Kunci baris menu dan kurangi stok jika dilacak serta increment jumlah terjual.
     *
     * @param  array<int, array{item: Item, quantity: int}>  $details
     * @throws ValidationException
     */
    private function prosesStokDanPenjualan(array $details): void
    {
        $kurang = [];
        foreach ($details as $detail) {
            /** @var Item $item */
            $item = Item::lockForUpdate()->find($detail['item']->id);
            if (! $item) {
                continue;
            }

            if ($item->lacakStok()) {
                if ($item->stock < $detail['quantity']) {
                    $kurang[] = "{$item->name} (sisa {$item->stock}, diminta {$detail['quantity']})";
                    continue;
                }
                $item->decrement('stock', $detail['quantity']);
            }

            // Tambahkan kuantitas terjual menu
            $item->increment('quantity', $detail['quantity']);
        }

        if ($kurang) {
            throw ValidationException::withMessages([
                'items' => 'Stok tidak cukup: ' . implode('; ', $kurang) . '.',
            ]);
        }
    }
}
