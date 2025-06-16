<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $primaryKey = 'id_products';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'category',
        'material_type',
        'in_stock',
        'is_active',
        'quantity',
        'image_url',
        'seller_id',
        'status',
    ];

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('order');
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class, 'seller_id', 'id_sellers');
    }
}
