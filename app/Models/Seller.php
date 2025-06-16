<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Seller extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'sellers';
    protected $primaryKey = 'id_sellers';

    protected $fillable = [
        'name_sellers',
        'email',
        'phone_number',
        'password',
        'address',
        'gender',
        'store_name',
        'ktp_image',
        'status',
        'city',
        'province',
        'profile_image',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
