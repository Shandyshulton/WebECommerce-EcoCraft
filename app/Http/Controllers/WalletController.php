<?php

namespace App\Http\Controllers;

use App\Models\CustomerVoucher;
use App\Models\Voucher;
use App\Services\RewardService;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    protected RewardService $rewards;

    public function __construct(RewardService $rewards)
    {
        $this->rewards = $rewards;
    }

    public function index()
    {
        $customer = Auth::guard('customer')->user();
        $customerId = $customer->getKey();

        $availableVouchers = CustomerVoucher::with('voucher')
            ->where('customer_id', $customerId)
            ->where('status', 'available')
            ->latest('claimed_at')
            ->get();

        $usedVouchers = CustomerVoucher::with(['voucher', 'order'])
            ->where('customer_id', $customerId)
            ->where('status', 'used')
            ->latest('used_at')
            ->take(10)
            ->get();

        $transactions = $customer->coinTransactions()->latest()->take(20)->get();

        $claimedVoucherIds = CustomerVoucher::where('customer_id', $customerId)->pluck('voucher_id');
        $claimableVouchers = Voucher::claimable()
            ->whereNotIn('id', $claimedVoucherIds)
            ->orderBy('title')
            ->get();

        return view('customer.wallet', [
            'customer' => $customer,
            'coinValue' => $this->rewards->coinValue(),
            'availableVouchers' => $availableVouchers,
            'usedVouchers' => $usedVouchers,
            'transactions' => $transactions,
            'claimableVouchers' => $claimableVouchers,
        ]);
    }

    public function claim(Voucher $voucher)
    {
        $customer = Auth::guard('customer')->user();
        $result = $this->rewards->claimVoucher($customer, $voucher);

        if ($result['error']) {
            return back()->with('error', $result['error']);
        }

        return back()->with('success', 'Voucher berhasil diklaim. Pakai saat checkout.');
    }
}
