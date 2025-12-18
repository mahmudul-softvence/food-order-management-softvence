<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'vendor_id',
        'food_id',
        'quantity',
        'unit_price',
        'total_price',
        'status',
        'payment_status',
        'note',
        'today_meal_id',
        'food_category_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public function food()
    {
        return $this->belongsTo(Food::class);
    }

    public function todayMeal()
    {
        return $this->belongsTo(TodayMeal::class);
    }

    protected static function booted()
    {
        static::creating(function ($order) {

            $vendor = str_pad($order->vendor_id, 5, '0', STR_PAD_LEFT);
            $emp    = str_pad($order->user_id, 5, '0', STR_PAD_LEFT);

            $lastOrder = Order::latest('id')->first();
            $next = $lastOrder ? $lastOrder->id + 1 : 1;

            $inc = str_pad($next, 5, '0', STR_PAD_LEFT);

            $order->order_no = "SV-{$vendor}-{$emp}-{$inc}";
        });
    }
}
