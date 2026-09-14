<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoinTransaction extends Model
{
    use HasFactory;

    protected $table = 'coin_transactions';

    protected $fillable = [
        'customer_id', 'order_id', 'type', 'amount', 'balance_after', 'description',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id_customers');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id_orders');
    }
}
