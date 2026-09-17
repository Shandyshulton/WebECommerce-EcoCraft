<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderTrackingEvent extends Model
{
    use HasFactory;

    protected $table = 'order_tracking_events';
    protected $primaryKey = 'id_events';

    protected $fillable = [
        'shipment_id', 'status', 'description', 'location', 'source', 'happened_at',
    ];

    protected $casts = [
        'happened_at' => 'datetime',
    ];

    public function shipment()
    {
        return $this->belongsTo(Shipment::class, 'shipment_id', 'id_shipments');
    }

    public function sourceLabel(): string
    {
        return match ($this->source) {
            Shipment::SOURCE_SELLER => 'Pengrajin',
            Shipment::SOURCE_ADMIN => 'Admin EcoCraft',
            Shipment::SOURCE_COURIER => 'Kurir',
            Shipment::SOURCE_CUSTOMER => 'Penerima',
            default => 'Sistem',
        };
    }
}
