<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Services\ShipmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourierTaskController extends Controller
{
    protected ShipmentService $shipments;

    public function __construct(ShipmentService $shipments)
    {
        $this->shipments = $shipments;
    }

    public function index()
    {
        $courier = Auth::guard('courier')->user();

        return view('courier.index', [
            'courier' => $courier,
            'tasks' => Shipment::with(['order', 'seller'])
                ->where('courier_user_id', $courier->getKey())
                ->whereNotIn('status', [Shipment::STATUS_DELIVERED, Shipment::STATUS_CANCELLED])
                ->oldest('id_shipments')
                ->get(),
            'finished' => Shipment::with(['order', 'seller'])
                ->where('courier_user_id', $courier->getKey())
                ->where('status', Shipment::STATUS_DELIVERED)
                ->latest('delivered_at')
                ->take(5)
                ->get(),
            'available' => Shipment::with(['order', 'seller'])
                ->claimable()
                ->oldest('id_shipments')
                ->get(),
        ]);
    }

    public function show(Shipment $shipment)
    {
        $this->authorizeTask($shipment);

        $shipment->load(['order.items.product', 'seller', 'events', 'deliveredBy']);

        return view('courier.show', [
            'shipment' => $shipment,
            'items' => $shipment->sellerItems(),
        ]);
    }

    public function claim(Shipment $shipment)
    {
        $courier = Auth::guard('courier')->user();

        if (! $this->shipments->claim($shipment, $courier)) {
            return redirect()->route('courier.tasks.index')
                ->with('error', 'Paket ini sudah diambil kurir lain atau belum dinyatakan siap.');
        }

        return redirect()->route('courier.tasks.show', $shipment)
            ->with('success', 'Paket berhasil diambil. Selamat mengantar!');
    }

    /**
     * Satu-satunya aksi kurir: menyatakan paket sudah sampai, dengan bukti foto.
     *
     * Status perjalanan tidak dipilih manual. Paket sudah berstatus `Shipped`
     * sejak kurir mengambil tugasnya, dan yang tersisa hanyalah konfirmasi tiba —
     * itu satu-satunya hal yang informasinya benar-benar dipegang kurir.
     */
    public function update(Request $request, Shipment $shipment)
    {
        $this->authorizeTask($shipment);

        if ($shipment->isDelivered()) {
            return redirect()->route('courier.tasks.show', $shipment)
                ->with('error', 'Paket ini sudah ditandai sampai.');
        }

        $data = $request->validate([
            'receiver_name' => ['required', 'string', 'max:100'],
            'proof_photo' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
        ], [
            'receiver_name.required' => 'Nama penerima wajib diisi.',
            'proof_photo.required' => 'Foto bukti wajib dilampirkan.',
        ]);

        // Pesanan yang belum dibayar tidak boleh berjalan lebih jauh.
        if ($shipment->order && $shipment->order->requiresPayment() && ! $shipment->order->isPaid()) {
            return back()->withInput()->withErrors([
                'receiver_name' => 'Pesanan ini belum dibayar, jadi paket belum bisa dinyatakan sampai.',
            ]);
        }

        $data['proof_photo'] = $request->file('proof_photo')->store('delivery-proofs', 'public');

        $this->shipments->markDelivered($shipment, $data, Shipment::SOURCE_COURIER);

        return redirect()->route('courier.tasks.show', $shipment)
            ->with('success', 'Paket ditandai sudah sampai.');
    }

    /**
     * Catat satu titik perjalanan paket tanpa mengubah statusnya.
     *
     * Hanya kurir yang mencatat ini karena dialah yang benar-benar memegang
     * paketnya; pengrajin tidak tahu posisi paket setelah diserahkan.
     */
    public function storeEvent(Request $request, Shipment $shipment)
    {
        $this->authorizeTask($shipment);

        $data = $request->validate([
            'description' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:120'],
        ]);

        // Status paket dipakai apa adanya sebagai penanda waktu. Yang dicatat
        // kurir di sini adalah posisi, bukan perubahan status.
        $this->shipments->addCheckpoint($shipment, [
            'status' => $shipment->status,
            'description' => $data['description'],
            'location' => $data['location'] ?? null,
        ], Shipment::SOURCE_COURIER);

        return redirect()
            ->route('courier.tasks.show', $shipment)
            ->with('success', 'Titik perjalanan paket ditambahkan.');
    }

    private function authorizeTask(Shipment $shipment): void
    {
        abort_unless(
            (int) $shipment->courier_user_id === (int) Auth::guard('courier')->id(),
            404
        );
    }
}
