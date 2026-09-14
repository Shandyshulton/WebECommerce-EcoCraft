<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductInquiryMessage extends Model
{
    use HasFactory;

    protected $table = 'product_inquiry_messages';
    protected $primaryKey = 'id_messages';

    protected $fillable = [
        'inquiry_id',
        'sender_type',
        'sender_id',
        'body',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function inquiry()
    {
        return $this->belongsTo(ProductInquiry::class, 'inquiry_id', 'id_inquiries');
    }

    public function isFromCustomer(): bool
    {
        return $this->sender_type === 'customer';
    }
}
