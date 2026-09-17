<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\PaymentService;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    protected PaymentService $payments;

    public function __construct(PaymentService $payments)
    {
        $this->payments = $payments;
    }

    public function show(Order $order)
    {
        $this->authorizeOrder($order);

        // COD tidak punya langkah pembayaran di muka.
        if (! $order->requiresPayment()) {
            return redirect()->route('track.track')->with('info', $order->paymentLabel().'.');
        }

        return view('payment.show', [
            'order' => $order,
            'virtualAccount' => $order->payment_method === Order::PAYMENT_TRANSFER
                ? $this->payments->virtualAccount($order)
                : null,
            'qrisPayload' => $order->payment_method === Order::PAYMENT_QRIS
                ? $this->payments->qrisPayload($order)
                : null,
        ]);
    }

    /**
     * Pembeli menyatakan sudah membayar.
     *
     * Ini simulasi: tidak ada verifikasi dari penyedia pembayaran, jadi status
     * berpindah atas dasar pernyataan pembeli sendiri.
     */
    public function confirm(Order $order)
    {
        $this->authorizeOrder($order);

        if (! $order->requiresPayment()) {
            return redirect()->route('track.track');
        }

        $this->payments->markPaid($order);

        return redirect()
            ->route('payment.show', $order)
            ->with('success', 'Pembayaran diterima. Terima kasih!');
    }

    private function authorizeOrder(Order $order): void
    {
        abort_unless(
            (int) $order->customer_id === (int) Auth::guard('customer')->id(),
            404
        );
    }
}
