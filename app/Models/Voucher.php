<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $table = 'vouchers';

    protected $fillable = [
        'code', 'title', 'description', 'type', 'value', 'min_spend', 'max_discount',
        'starts_at', 'expires_at', 'usage_limit', 'used_count', 'per_customer_limit',
        'is_claimable', 'is_reward', 'is_active',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_spend' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_claimable' => 'boolean',
        'is_reward' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function customerVouchers()
    {
        return $this->hasMany(CustomerVoucher::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)
            ->where(fn ($q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', now()))
            ->where(fn ($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>=', now()))
            ->where(fn ($q) => $q->whereNull('usage_limit')->orWhereColumn('used_count', '<', 'usage_limit'));
    }

    public function scopeClaimable(Builder $query): Builder
    {
        return $query->active()->where('is_claimable', true)->where('is_reward', false);
    }

    public function discountFor(float $subtotal): float
    {
        if ($this->type === 'percent') {
            $discount = $subtotal * ((float) $this->value / 100);
            if ($this->max_discount !== null) {
                $discount = min($discount, (float) $this->max_discount);
            }
        } else {
            $discount = (float) $this->value;
        }

        return round(min($discount, $subtotal), 2);
    }
}
