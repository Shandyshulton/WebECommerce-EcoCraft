<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $primaryKey = 'id_orders';

    protected $fillable = [
        'customer_id', 'customer_voucher_id', 'coupon_code', 'order_number', 'customer_name',
        'customer_email', 'customer_phone',
        'shipping_address', 'shipping_city', 'shipping_province', 'shipping_postal_code',
        'shipping_method', 'payment_method', 'subtotal', 'voucher_discount', 'coins_used',
        'coin_discount', 'discount_total', 'coins_earned', 'total', 'status',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'voucher_discount' => 'decimal:2',
        'coin_discount' => 'decimal:2',
        'discount_total' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id_orders');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id_customers');
    }

    public function customerVoucher()
    {
        return $this->belongsTo(CustomerVoucher::class, 'customer_voucher_id');
    }
}
