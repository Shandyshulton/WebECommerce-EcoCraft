<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Shipment extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'Pending';
    public const STATUS_PACKED = 'Packed';
    public const STATUS_SHIPPED = 'Shipped';
    public const STATUS_IN_TRANSIT = 'In Transit';
    public const STATUS_DELIVERED = 'Delivered';
    public const STATUS_CANCELLED = 'Cancelled';

    public const STATUSES = [
        self::STATUS_PENDING,
        self::STATUS_PACKED,
        self::STATUS_SHIPPED,
        self::STATUS_IN_TRANSIT,
        self::STATUS_DELIVERED,
        self::STATUS_CANCELLED,
    ];

    public const SOURCE_SYSTEM = 'system';
    public const SOURCE_SELLER = 'seller';
    public const SOURCE_ADMIN = 'admin';
    public const SOURCE_COURIER = 'courier';

    protected $table = 'shipments';
    protected $primaryKey = 'id_shipments';

    protected $fillable = [
        'order_id', 'seller_id', 'courier_id', 'tracking_number',
        'status', 'note', 'shipped_at', 'delivered_at',
    ];

    protected $casts = [
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id_orders');
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class, 'seller_id', 'id_sellers');
    }

    public function courier()
    {
        return $this->belongsTo(Courier::class, 'courier_id', 'id_couriers');
    }

    public function events()
    {
        return $this->hasMany(OrderTrackingEvent::class, 'shipment_id', 'id_shipments')
            ->orderBy('happened_at')
            ->orderBy('id_events');
    }

    /**
     * Barang milik pengrajin ini di dalam pesanan terkait.
     */
    public function sellerItems(): Collection
    {
        $order = $this->order;

        if (! $order) {
            return collect();
        }

        if (! $order->relationLoaded('items')) {
            $order->load('items');
        }

        return $order->items->where('seller_id', $this->seller_id)->values();
    }

    public function isDelivered(): bool
    {
        return $this->status === self::STATUS_DELIVERED;
    }

    /**
     * Tautan ke halaman pelacakan milik ekspedisi.
     */
    public function trackingUrl(): ?string
    {
        $template = $this->courier?->tracking_url;

        if (! $template) {
            return null;
        }

        return str_replace('{resi}', rawurlencode((string) $this->tracking_number), $template);
    }

    public function statusClass(): string
    {
        return match ($this->status) {
            self::STATUS_DELIVERED => 'ok',
            self::STATUS_CANCELLED => 'off',
            self::STATUS_SHIPPED, self::STATUS_IN_TRANSIT => 'info',
            default => 'warn',
        };
    }
}
