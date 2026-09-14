<?php

namespace App\Services;

use App\Models\CoinTransaction;
use App\Models\Customer;
use App\Models\CustomerVoucher;
use App\Models\Order;
use App\Models\Voucher;

class RewardService
{
    public function coinValue(): int
    {
        return max(1, (int) config('rewards.coin_value'));
    }

    public function earnPerAmount(): int
    {
        return max(1, (int) config('rewards.coin_earn_per'));
    }

    public function maxDiscount(float $subtotal): float
    {
        return round($subtotal * (float) config('rewards.max_discount_ratio'), 2);
    }

    public function coinsEarnedFor(float $amount): int
    {
        return intdiv((int) floor($amount), $this->earnPerAmount());
    }

    /**
     * Cairkan koin dari sebuah order. Idempotent: satu order hanya menghasilkan satu baris 'earn'.
     */
    public function earnFromOrder(Customer $customer, Order $order): int
    {
        $already = CoinTransaction::where('order_id', $order->getKey())->where('type', 'earn')->exists();
        if ($already) {
            return 0;
        }

        $coins = $this->coinsEarnedFor((float) $order->subtotal);
        if ($coins < 1) {
            return 0;
        }

        $customer->increment('coin_balance', $coins);

        CoinTransaction::create([
            'customer_id' => $customer->getKey(),
            'order_id' => $order->getKey(),
            'type' => 'earn',
            'amount' => $coins,
            'balance_after' => $customer->coin_balance,
            'description' => 'Koin dari pesanan '.$order->order_number,
        ]);

        return $coins;
    }

    /**
     * Tukar koin sebagai potongan. Mengembalikan nilai rupiah potongan (0 bila saldo kurang).
     */
    public function redeemCoins(Customer $customer, Order $order, int $coins): float
    {
        if ($coins < 1 || $customer->coin_balance < $coins) {
            return 0.0;
        }

        $customer->decrement('coin_balance', $coins);

        CoinTransaction::create([
            'customer_id' => $customer->getKey(),
            'order_id' => $order->getKey(),
            'type' => 'redeem',
            'amount' => $coins,
            'balance_after' => $customer->coin_balance,
            'description' => 'Potongan pesanan '.$order->order_number,
        ]);

        return $coins * $this->coinValue();
    }

    /**
     * Resolusi voucher untuk checkout, dari kupon dompet atau kode voucher.
     *
     * @param  bool  $persist  false = dry-run (tidak mengklaim voucher baru), dipakai untuk pratinjau.
     * @return array{customer_voucher: ?CustomerVoucher, voucher: ?Voucher, discount: float, error: ?string}
     */
    public function resolveVoucher(Customer $customer, ?string $code, ?int $customerVoucherId, float $subtotal, bool $persist = true): array
    {
        $none = ['customer_voucher' => null, 'voucher' => null, 'discount' => 0.0, 'error' => null];

        if ($customerVoucherId) {
            $owned = CustomerVoucher::with('voucher')
                ->where('customer_id', $customer->getKey())
                ->where('status', 'available')
                ->find($customerVoucherId);

            if (! $owned) {
                return ['customer_voucher' => null, 'voucher' => null, 'discount' => 0.0, 'error' => 'Kupon tidak ditemukan atau sudah terpakai.'];
            }

            return $this->validateCustomerVoucher($owned, $subtotal);
        }

        $code = trim((string) $code);
        if ($code === '') {
            return $none;
        }

        $voucher = Voucher::where('code', $code)->first();
        if (! $voucher) {
            return ['customer_voucher' => null, 'voucher' => null, 'discount' => 0.0, 'error' => 'Kode voucher tidak dikenal.'];
        }

        $owned = CustomerVoucher::with('voucher')
            ->where('customer_id', $customer->getKey())
            ->where('voucher_id', $voucher->getKey())
            ->where('status', 'available')
            ->first();

        if ($owned) {
            return $this->validateCustomerVoucher($owned, $subtotal);
        }

        if (! $persist) {
            if (! $voucher->is_claimable || $voucher->is_reward) {
                return ['customer_voucher' => null, 'voucher' => null, 'discount' => 0.0, 'error' => 'Voucher ini tidak bisa diklaim.'];
            }
            $ownedCount = CustomerVoucher::where('customer_id', $customer->getKey())
                ->where('voucher_id', $voucher->getKey())
                ->count();
            if ($ownedCount >= $voucher->per_customer_limit) {
                return ['customer_voucher' => null, 'voucher' => null, 'discount' => 0.0, 'error' => 'Kamu sudah memiliki voucher ini.'];
            }

            $check = $this->validateVoucherModel($voucher, $subtotal);
            if ($check['error']) {
                return ['customer_voucher' => null, 'voucher' => null, 'discount' => 0.0, 'error' => $check['error']];
            }

            return ['customer_voucher' => null, 'voucher' => $voucher, 'discount' => $check['discount'], 'error' => null];
        }

        $claim = $this->claimVoucher($customer, $voucher);
        if ($claim['error']) {
            return ['customer_voucher' => null, 'voucher' => null, 'discount' => 0.0, 'error' => $claim['error']];
        }

        return $this->validateCustomerVoucher($claim['customer_voucher'], $subtotal);
    }

    /**
     * @return array{customer_voucher: ?CustomerVoucher, voucher: ?Voucher, discount: float, error: ?string}
     */
    private function validateCustomerVoucher(CustomerVoucher $customerVoucher, float $subtotal): array
    {
        $voucher = $customerVoucher->voucher;
        if (! $voucher) {
            return ['customer_voucher' => null, 'voucher' => null, 'discount' => 0.0, 'error' => 'Voucher tidak ditemukan.'];
        }

        $check = $this->validateVoucherModel($voucher, $subtotal);
        if ($check['error']) {
            return ['customer_voucher' => null, 'voucher' => null, 'discount' => 0.0, 'error' => $check['error']];
        }

        return ['customer_voucher' => $customerVoucher, 'voucher' => $voucher, 'discount' => $check['discount'], 'error' => null];
    }

    /**
     * @return array{discount: float, error: ?string}
     */
    private function validateVoucherModel(Voucher $voucher, float $subtotal): array
    {
        if (! $voucher->is_active) {
            return ['discount' => 0.0, 'error' => 'Voucher tidak aktif.'];
        }
        if ($voucher->starts_at && $voucher->starts_at->isFuture()) {
            return ['discount' => 0.0, 'error' => 'Voucher belum berlaku.'];
        }
        if ($voucher->expires_at && $voucher->expires_at->isPast()) {
            return ['discount' => 0.0, 'error' => 'Voucher sudah kedaluwarsa.'];
        }
        if ($voucher->usage_limit !== null && $voucher->used_count >= $voucher->usage_limit) {
            return ['discount' => 0.0, 'error' => 'Kuota voucher sudah habis.'];
        }
        if ($subtotal < (float) $voucher->min_spend) {
            return ['discount' => 0.0, 'error' => 'Minimal belanja Rp '.number_format((float) $voucher->min_spend, 0, ',', '.').' untuk memakai voucher ini.'];
        }

        return ['discount' => $voucher->discountFor($subtotal), 'error' => null];
    }

    public function markVoucherUsed(CustomerVoucher $customerVoucher, Order $order): void
    {
        $customerVoucher->update([
            'status' => 'used',
            'order_id' => $order->getKey(),
            'used_at' => now(),
        ]);

        $customerVoucher->voucher?->increment('used_count');
    }

    /**
     * @return array{customer_voucher: ?CustomerVoucher, error: ?string}
     */
    public function claimVoucher(Customer $customer, Voucher $voucher): array
    {
        if (! $voucher->is_active || ! $voucher->is_claimable || $voucher->is_reward) {
            return ['customer_voucher' => null, 'error' => 'Voucher ini tidak bisa diklaim.'];
        }
        if ($voucher->starts_at && $voucher->starts_at->isFuture()) {
            return ['customer_voucher' => null, 'error' => 'Voucher belum berlaku.'];
        }
        if ($voucher->expires_at && $voucher->expires_at->isPast()) {
            return ['customer_voucher' => null, 'error' => 'Voucher sudah kedaluwarsa.'];
        }
        if ($voucher->usage_limit !== null && $voucher->used_count >= $voucher->usage_limit) {
            return ['customer_voucher' => null, 'error' => 'Kuota voucher sudah habis.'];
        }

        $owned = CustomerVoucher::where('customer_id', $customer->getKey())
            ->where('voucher_id', $voucher->getKey())
            ->count();
        if ($owned >= $voucher->per_customer_limit) {
            return ['customer_voucher' => null, 'error' => 'Kamu sudah memiliki voucher ini.'];
        }

        $customerVoucher = CustomerVoucher::create([
            'voucher_id' => $voucher->getKey(),
            'customer_id' => $customer->getKey(),
            'source' => 'claim',
            'status' => 'available',
            'claimed_at' => now(),
        ]);

        return ['customer_voucher' => $customerVoucher, 'error' => null];
    }

    /**
     * Terbitkan voucher reward tiap kelipatan pesanan Delivered.
     */
    public function grantMilestoneVouchers(Customer $customer): int
    {
        $every = max(1, (int) config('rewards.voucher_reward_every'));
        $template = Voucher::where('is_reward', true)->where('is_active', true)->first();
        if (! $template) {
            return 0;
        }

        $delivered = Order::where('customer_id', $customer->getKey())->where('status', 'Delivered')->count();
        $earned = intdiv($delivered, $every);
        $granted = 0;

        while ($customer->reward_milestones < $earned) {
            CustomerVoucher::create([
                'voucher_id' => $template->getKey(),
                'customer_id' => $customer->getKey(),
                'source' => 'reward',
                'status' => 'available',
                'claimed_at' => now(),
            ]);
            $customer->increment('reward_milestones');
            $granted++;
        }

        return $granted;
    }
}
