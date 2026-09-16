<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Buatkan data pengiriman untuk pesanan yang sudah ada sebelum fitur ini.
     */
    public function up(): void
    {
        DB::table('orders')->orderBy('id_orders')->each(function ($order) {
            $sellerIds = DB::table('order_items')
                ->where('order_id', $order->id_orders)
                ->distinct()
                ->pluck('seller_id');

            foreach ($sellerIds as $sellerId) {
                $exists = DB::table('shipments')
                    ->where('order_id', $order->id_orders)
                    ->where('seller_id', $sellerId)
                    ->exists();

                if ($exists) {
                    continue;
                }

                $happenedAt = $order->created_at ?? now();

                $shipmentId = DB::table('shipments')->insertGetId([
                    'order_id' => $order->id_orders,
                    'seller_id' => $sellerId,
                    'courier_id' => null,
                    'tracking_number' => null,
                    'status' => 'Pending',
                    'note' => null,
                    'shipped_at' => null,
                    'delivered_at' => null,
                    'created_at' => $happenedAt,
                    'updated_at' => $happenedAt,
                ]);

                DB::table('order_tracking_events')->insert([
                    'shipment_id' => $shipmentId,
                    'status' => 'Pending',
                    'description' => 'Paket disiapkan oleh pengrajin.',
                    'location' => null,
                    'source' => 'system',
                    'happened_at' => $happenedAt,
                    'created_at' => $happenedAt,
                    'updated_at' => $happenedAt,
                ]);
            }
        });
    }

    public function down(): void
    {
        // Data pengiriman tidak dihapus agar riwayat pelacakan tidak hilang.
    }
};
