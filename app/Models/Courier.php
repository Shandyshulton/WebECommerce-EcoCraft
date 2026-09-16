<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Courier extends Model
{
    use HasFactory;

    protected $table = 'couriers';
    protected $primaryKey = 'id_couriers';

    protected $fillable = [
        'code', 'name', 'tracking_url', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function shipments()
    {
        return $this->hasMany(Shipment::class, 'courier_id', 'id_couriers');
    }
}
