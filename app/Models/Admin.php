<?php

// app/Models/Admin.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'admins';
    protected $primaryKey = 'id_admins';

    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'password',
        'address',
        'gender',
        'profile_image',
    ];

    public function getProfileImageUrlAttribute()
{
    return $this->profile_image ? asset('storage/'.$this->profile_image) : asset('images/default-profile.png');
}

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}

