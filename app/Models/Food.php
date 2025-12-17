<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    protected $fillable = [
        'name',
        'description',
        'image',
        'status',
        'user_id'
    ];

    public function todayMeals()
    {
        return $this->hasMany(TodayMeal::class);
    }

    public function todayMeal()
    {
        return $this->hasOne(TodayMeal::class)->whereDate('created_at', today());
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vendor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(FoodCategory::class, 'food_category_id');
    }
}
