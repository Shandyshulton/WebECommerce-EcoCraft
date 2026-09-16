<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Shipment;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ShipmentService
{
    /**
     * Pastikan setiap pengrajin yang punya barang di pesanan ini memiliki data pengiriman.
     * Idempoten: aman dipanggil berulang kali.
     */
    public function syncForOrder(Order $order): void
    {
        $sellerIds = $order->items()->distinct()->pluck('seller_id')->filter();

        foreach ($sellerIds as $sellerId) {
            $shipment = Shipment::firstOrCreate(
                [
                    'order_id' => $order->getKey(),
                    'seller_id' => $sellerId,
                ],
                [
                    'status' => Shipment::STATUS_PENDING,
                ]
            );

            if ($shipment->wasRecentlyCreated) {
                $this->recordEvent(
                    $shipment,
                    Shipment::STATUS_PENDING,
                    'Paket disiapkan oleh pengrajin.',
                    null,
                    Shipment::SOURCE_SYSTEM
                );
            }
        }
    }

    /**
     * Perbarui data pengiriman dari sisi pengrajin.
     */
    public function updateShipment(Shipment $shipment, array $data): Shipment
    {
        return $this->persist($shipment, $data, false);
    }

    /**
     * Catat satu titik perjalanan paket (mis. tiba di kota transit).
     */
    public function addCheckpoint(Shipment $shipment, array $data): Shipment
    {
        return $this->persist($shipment, array_merge($data, [
            'courier_id' => $shipment->courier_id,
            'tracking_number' => $shipment->tracking_number,
            'note' => $shipment->note,
        ]), true);
    }

    private function persist(Shipment $shipment, array $data, bool $alwaysRecordEvent): Shipment
    {
        return DB::transaction(function () use ($shipment, $data, $alwaysRecordEvent) {
            $previousStatus = $shipment->status;
            $status = $data['status'];

            $attributes = [
                'courier_id' => $data['courier_id'] ?? null,
                'tracking_number' => $data['tracking_number'] ?? null,
                'note' => $data['note'] ?? null,
                'status' => $status,
            ];

            if ($status === Shipment::STATUS_SHIPPED && ! $shipment->shipped_at) {
                $attributes['shipped_at'] = now();
            }

            if ($status === Shipment::STATUS_DELIVERED) {
                $attributes['shipped_at'] = $shipment->shipped_at ?? now();
                $attributes['delivered_at'] = $shipment->delivered_at ?? now();
            }

            $shipment->update($attributes);
            $shipment->refresh();

            if ($alwaysRecordEvent || $previousStatus !== $status) {
                $this->recordEvent(
                    $shipment,
                    $status,
                    $data['description'] ?? $this->defaultDescription($shipment),
                    $data['location'] ?? null,
                    Shipment::SOURCE_SELLER
                );
            }

            $this->syncOrderStatus($shipment->order);

            return $shipment;
        });
    }

    /**
     * Selaraskan status pesanan dari status seluruh pengirimannya.
     *
     * Sebelum ini, satu pengrajin menandai "Shipped" akan menandai seluruh
     * pesanan — termasuk barang pengrajin lain yang belum dikirim.
     */
    public function syncOrderStatus(?Order $order): void
    {
        if (! $order) {
            return;
        }

        $statuses = $order->shipments()->pluck('status');

        if ($statuses->isEmpty()) {
            return;
        }

        $aggregate = $this->aggregateStatus($statuses);

        if ($aggregate !== null && $order->status !== $aggregate) {
            $order->update(['status' => $aggregate]);
        }
    }

    private function aggregateStatus(Collection $statuses): ?string
    {
        if ($statuses->every(fn ($status) => $status === Shipment::STATUS_DELIVERED)) {
            return 'Delivered';
        }

        if ($statuses->every(fn ($status) => $status === Shipment::STATUS_CANCELLED)) {
            return 'Cancelled';
        }

        // 'Delivered' ikut dihitung: kalau sebagian paket sudah tiba sementara
        // paket lain belum dikirim, pesanan tidak boleh turun kembali ke Processing.
        $moved = [Shipment::STATUS_SHIPPED, Shipment::STATUS_IN_TRANSIT, Shipment::STATUS_DELIVERED];

        if ($statuses->contains(fn ($status) => in_array($status, $moved, true))) {
            return 'Shipped';
        }

        return 'Processing';
    }

    public function recordEvent(Shipment $shipment, string $status, string $description, ?string $location, string $source): void
    {
        $shipment->events()->create([
            'status' => $status,
            'description' => $description,
            'location' => $location,
            'source' => $source,
            'happened_at' => now(),
        ]);
    }

    private function defaultDescription(Shipment $shipment): string
    {
        return match ($shipment->status) {
            Shipment::STATUS_PACKED => 'Paket dikemas dan siap diserahkan ke kurir.',
            Shipment::STATUS_SHIPPED => 'Paket diserahkan ke '.($shipment->courier->name ?? 'kurir').'.',
            Shipment::STATUS_IN_TRANSIT => 'Paket dalam perjalanan menuju alamat tujuan.',
            Shipment::STATUS_DELIVERED => 'Paket diterima oleh penerima.',
            Shipment::STATUS_CANCELLED => 'Pengiriman dibatalkan.',
            default => 'Status pengiriman diperbarui.',
        };
    }
}
