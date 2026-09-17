<?php

namespace App\Http\Controllers;

use App\Models\Courier;
use App\Models\Order;
use App\Models\Shipment;
use App\Services\ShipmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerShipmentController extends Controller
{
    protected ShipmentService $shipments;

    public function __construct(ShipmentService $shipments)
    {
        $this->shipments = $shipments;
    }

    public function index(Request $request)
    {
        $sellerId = Auth::guard('seller')->id();

        // Lengkapi data pengiriman untuk pesanan lama yang belum memilikinya.
        Order::with('items')
            ->whereHas('items', fn ($query) => $query->where('seller_id', $sellerId))
            ->get()
            ->each(fn ($order) => $this->shipments->syncForOrder($order));

        $query = Shipment::with(['order', 'courier'])->where('seller_id', $sellerId);

        if ($search = trim((string) $request->get('q'))) {
            $query->where(function ($inner) use ($search) {
                $inner->where('tracking_number', 'like', "%{$search}%")
                    ->orWhereHas('order', function ($order) use ($search) {
                        $order->where('order_number', 'like', "%{$search}%")
                            ->orWhere('customer_name', 'like', "%{$search}%");
                    });
            });
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $shipments = $query->latest('id_shipments')->paginate(15)->withQueryString();

        $base = Shipment::where('seller_id', $sellerId);
        $stats = [
            'total' => (clone $base)->count(),
            'pending' => (clone $base)->whereIn('status', [Shipment::STATUS_PENDING, Shipment::STATUS_PACKED])->count(),
            'shipped' => (clone $base)->whereIn('status', [Shipment::STATUS_SHIPPED, Shipment::STATUS_IN_TRANSIT])->count(),
            'delivered' => (clone $base)->where('status', Shipment::STATUS_DELIVERED)->count(),
        ];

        return view('seller.shipments.index', [
            'shipments' => $shipments,
            'stats' => $stats,
            'statuses' => Shipment::STATUSES,
        ]);
    }

    public function show(Shipment $shipment)
    {
        $this->authorizeShipment($shipment);

        $shipment->load(['order.items.product', 'courier', 'events', 'seller']);

        $couriers = Courier::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('seller.shipments.show', [
            'shipment' => $shipment,
            'couriers' => $couriers,
            'items' => $shipment->sellerItems(),
            'trackingUrl' => $shipment->trackingUrl(),
        ]);
    }

    public function update(Request $request, Shipment $shipment)
    {
        $this->authorizeShipment($shipment);

        // Setelah paket diserahkan, pengelolaannya ada di kurir atau ekspedisi.
        if (in_array($shipment->status, [Shipment::STATUS_SHIPPED, Shipment::STATUS_IN_TRANSIT, Shipment::STATUS_DELIVERED], true)) {
            return back()->with('error', 'Paket sudah diserahkan. Status selanjutnya dikelola kurir atau ekspedisi.');
        }

        $data = $request->validate([
            'courier_id' => ['required', 'integer', 'exists:couriers,id_couriers'],
            'tracking_number' => ['nullable', 'string', 'max:60'],
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        $courier = Courier::findOrFail($data['courier_id']);
        $errors = [];

        if ($shipment->order && $shipment->order->requiresPayment() && ! $shipment->order->isPaid()) {
            $errors['courier_id'] = 'Pesanan ini belum dibayar. Tunggu pembayaran masuk sebelum mengirim.';
        }

        if ($courier->is_local_delivery) {
            // Pengiriman sendiri: pengrajin hanya menyatakan paket siap diambil.
            // Status perjalanan selanjutnya dikelola petugas kurir.
            $data['status'] = Shipment::STATUS_PACKED;
        } else {
            if (empty($data['tracking_number'])) {
                $errors['tracking_number'] = 'Nomor resi wajib diisi saat menyerahkan paket ke ekspedisi.';
            }

            // Menyerahkan paket ke ekspedisi berarti paket mulai dikirim.
            $data['status'] = Shipment::STATUS_SHIPPED;
        }

        if ($errors) {
            return back()->withInput()->withErrors($errors);
        }

        $this->shipments->updateShipment($shipment, $data);

        return redirect()
            ->route('seller.shipments.show', $shipment)
            ->with('success', $courier->is_local_delivery
                ? 'Paket ditandai siap diambil kurir.'
                : 'Paket diserahkan ke '.$courier->name.'.');
    }

    private function authorizeShipment(Shipment $shipment): void
    {
        abort_unless((int) $shipment->seller_id === (int) Auth::guard('seller')->id(), 404);
    }
}
