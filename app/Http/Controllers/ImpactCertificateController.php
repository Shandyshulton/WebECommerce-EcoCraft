<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Services\ImpactService;
use Illuminate\Support\Facades\Auth;

class ImpactCertificateController extends Controller
{
    protected ImpactService $impact;

    public function __construct(ImpactService $impact)
    {
        $this->impact = $impact;
    }

    public function show()
    {
        $customer = Auth::guard('customer')->user();

        $orders = Order::with('items.product')
            ->where('customer_id', $customer->getKey())
            ->get();

        return view('customer.certificate', [
            'customer' => $customer,
            'impact' => $this->impact->forCustomer($customer, $orders),
            'certificate' => [
                'number' => $this->certificateNumber($customer),
                'issued_at' => now(),
                'period_start' => $orders->min('created_at') ?? now(),
                'period_end' => now(),
            ],
        ]);
    }

    /**
     * Nomor sertifikat stabil untuk satu customer, sehingga bisa dirujuk ulang.
     */
    private function certificateNumber(Customer $customer): string
    {
        return 'ECO-IMP-'.strtoupper(substr(sha1($customer->getKey().'|'.$customer->email), 0, 10));
    }
}
