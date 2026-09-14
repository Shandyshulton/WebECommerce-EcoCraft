<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductInquiry extends Model
{
    use HasFactory;

    protected $table = 'product_inquiries';
    protected $primaryKey = 'id_inquiries';

    protected $fillable = [
        'product_id',
        'seller_id',
        'customer_id',
        'subject',
        'last_message_at',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id_products');
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class, 'seller_id', 'id_sellers');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id_customers');
    }

    public function messages()
    {
        return $this->hasMany(ProductInquiryMessage::class, 'inquiry_id', 'id_inquiries')->orderBy('created_at');
    }

    public function latestMessage()
    {
        return $this->hasOne(ProductInquiryMessage::class, 'inquiry_id', 'id_inquiries')->latestOfMany('created_at');
    }
}
