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
        'waste_factor',
        'carbon_factor',
        'in_stock',
        'is_active',
        'quantity',
        'image_url',
        'image_gallery',
        'seller_id',
        'status',
    ];

    protected $casts = [
        'waste_factor' => 'decimal:2',
        'carbon_factor' => 'decimal:2',
        'image_gallery' => 'array',
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
