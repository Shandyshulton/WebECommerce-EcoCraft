<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarrantyClaim extends Model
{
    use HasFactory;

    protected $table = 'warranty_claims';
    protected $primaryKey = 'id_claims';

    protected $fillable = [
        'order_id', 'order_item_id', 'customer_id', 'seller_id', 'product_id',
        'category', 'description', 'photo', 'status', 'resolution', 'responded_at',
    ];

    protected $casts = [
        'responded_at' => 'datetime',
    ];

    public const CATEGORIES = [
        'kerusakan' => 'Rusak saat diterima',
        'jahitan' => 'Jahitan / anyaman lepas',
        'tidak_sesuai' => 'Tidak sesuai deskripsi',
        'lainnya' => 'Lainnya',
    ];

    public const STATUSES = [
        'Submitted' => 'Diajukan',
        'Reviewing' => 'Sedang diperiksa',
        'Approved' => 'Disetujui',
        'Rejected' => 'Ditolak',
        'Completed' => 'Selesai',
    ];

    /**
     * Status pesanan yang itemnya sudah boleh diklaim garansi:
     * barang sudah dikirim atau sudah diterima.
     */
    public const ELIGIBLE_ORDER_STATUSES = ['Shipped', 'Delivered'];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id_orders');
    }

    public function item()
    {
        return $this->belongsTo(OrderItem::class, 'order_item_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id_customers');
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class, 'seller_id', 'id_sellers');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id_products');
    }

    public function categoryLabel(): string
    {
        return self::CATEGORIES[$this->category] ?? $this->category;
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function statusTone(): string
    {
        return match ($this->status) {
            'Submitted' => 'warn',
            'Reviewing' => 'info',
            'Approved', 'Completed' => 'ok',
            'Rejected' => 'off',
            default => 'neutral',
        };
    }
}
