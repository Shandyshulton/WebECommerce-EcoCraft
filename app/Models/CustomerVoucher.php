<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerVoucher extends Model
{
    use HasFactory;

    protected $table = 'customer_vouchers';

    protected $fillable = [
        'voucher_id', 'customer_id', 'source', 'status', 'order_id', 'claimed_at', 'used_at',
    ];

    protected $casts = [
        'claimed_at' => 'datetime',
        'used_at' => 'datetime',
    ];

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id_customers');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id_orders');
    }
}
