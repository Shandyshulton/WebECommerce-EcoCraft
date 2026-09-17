<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public const PAYMENT_COD = 'COD';
    public const PAYMENT_TRANSFER = 'Transfer Bank';
    public const PAYMENT_QRIS = 'QRIS';

    public const PAYMENT_UNPAID = 'Unpaid';
    public const PAYMENT_PAID = 'Paid';

    protected $table = 'orders';
    protected $primaryKey = 'id_orders';

    protected $fillable = [
        'customer_id', 'customer_voucher_id', 'coupon_code', 'order_number', 'customer_name',
        'customer_email', 'customer_phone',
        'shipping_address', 'shipping_city', 'shipping_province', 'shipping_postal_code',
        'shipping_method', 'payment_method', 'payment_status', 'virtual_account', 'paid_at',
        'subtotal', 'voucher_discount', 'coins_used',
        'coin_discount', 'discount_total', 'coins_earned', 'total', 'status',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'voucher_discount' => 'decimal:2',
        'coin_discount' => 'decimal:2',
        'discount_total' => 'decimal:2',
        'total' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    /**
     * Metode yang memerlukan pembayaran sebelum pesanan diproses.
     */
    public function requiresPayment(): bool
    {
        return in_array($this->payment_method, [self::PAYMENT_TRANSFER, self::PAYMENT_QRIS], true);
    }

    public function isPaid(): bool
    {
        return $this->payment_status === self::PAYMENT_PAID;
    }

    public function paymentLabel(): string
    {
        return match ($this->payment_status) {
            self::PAYMENT_PAID => 'Sudah dibayar',
            default => $this->payment_method === self::PAYMENT_COD ? 'Bayar saat diterima' : 'Belum dibayar',
        };
    }

    public function paymentClass(): string
    {
        if ($this->isPaid()) {
            return 'ok';
        }

        return $this->payment_method === self::PAYMENT_COD ? 'info' : 'warn';
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id_orders');
    }

    public function shipments()
    {
        return $this->hasMany(Shipment::class, 'order_id', 'id_orders');
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
