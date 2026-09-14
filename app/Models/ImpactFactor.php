<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImpactFactor extends Model
{
    use HasFactory;

    protected $table = 'impact_factors';

    protected $fillable = [
        'material_type',
        'waste_per_item',
        'carbon_per_item',
    ];

    protected $casts = [
        'waste_per_item' => 'decimal:2',
        'carbon_per_item' => 'decimal:2',
    ];
}
