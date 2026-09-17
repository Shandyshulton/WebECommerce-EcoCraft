<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class CourierUser extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'courier_users';
    protected $primaryKey = 'id_courier_users';

    protected $fillable = [
        'courier_id',
        'name',
        'email',
        'phone_number',
        'password',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Jasa ekspedisi tempat petugas ini bernaung.
     */
    public function courier()
    {
        return $this->belongsTo(Courier::class, 'courier_id', 'id_couriers');
    }

    public function shipments()
    {
        return $this->hasMany(Shipment::class, 'courier_user_id', 'id_courier_users');
    }
}
