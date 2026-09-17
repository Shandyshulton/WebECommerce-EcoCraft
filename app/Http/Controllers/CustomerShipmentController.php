<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Services\ShipmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerShipmentController extends Controller
{
    protected ShipmentService $shipments;

    public function __construct(ShipmentService $shipments)
    {
        $this->shipments = $shipments;
    }

    /**
     * Penerima menyatakan paket sudah tiba, sekaligus mengunggah bukti.
     *
     * Hanya penerima yang benar-benar tahu paket sampai, sehingga status
     * "Delivered" datang dari sini dan bukan dari pengrajin.
     */
    public function confirm(Request $request, Shipment $shipment)
    {
        $this->authorizeShipment($shipment);

        if (! in_array($shipment->status, [Shipment::STATUS_SHIPPED, Shipment::STATUS_IN_TRANSIT], true)) {
            return back()->with('error', 'Paket ini belum dalam perjalanan, jadi belum bisa dikonfirmasi.');
        }

        $data = $request->validate([
            'receiver_name' => ['required', 'string', 'max:100'],
            'proof_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
        ], [
            'receiver_name.required' => 'Nama penerima wajib diisi.',
            'proof_photo.image' => 'Bukti harus berupa gambar.',
        ]);

        if ($request->hasFile('proof_photo')) {
            $data['proof_photo'] = $request->file('proof_photo')->store('delivery-proofs', 'public');
        }

        $this->shipments->markDelivered($shipment, $data, Shipment::SOURCE_CUSTOMER);

        return back()->with('success', 'Terima kasih! Penerimaan paket sudah dikonfirmasi.');
    }

    private function authorizeShipment(Shipment $shipment): void
    {
        $customerId = Auth::guard('customer')->id();

        abort_unless(
            $shipment->order()->where('customer_id', $customerId)->exists(),
            404
        );
    }
}
