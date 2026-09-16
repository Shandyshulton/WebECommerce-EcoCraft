<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerAddress extends Model
{
    use HasFactory;

    protected $table = 'customer_addresses';
    protected $primaryKey = 'id_addresses';

    protected $fillable = [
        'customer_id', 'label', 'recipient_name', 'phone',
        'address', 'city', 'province', 'postal_code', 'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id_customers');
    }

    public function scopeDefault(Builder $query): Builder
    {
        return $query->where('is_default', true);
    }

    public function labelText(): string
    {
        return $this->label ?: 'Alamat';
    }
}
