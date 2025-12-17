<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TodayMeal extends Model
{
    protected $fillable = ['food_id', 'price', 'stock', 'status', 'order_count', 'food_category_id', 'vendor_id'];

    public function food()
    {
        return $this->belongsTo(Food::class);
    }

    public function category()
    {
        return $this->belongsTo(FoodCategory::class, 'food_category_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }


    protected static function booted()
    {
        static::creating(function ($model) {
            $model->vendor_id = Auth::id();
        });
    }
}
