<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FoodCategory extends Model
{


    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time'   => 'datetime:H:i',
    ];

    protected $fillable = [
        'image',
        'name',
        'description',
        'start_time',
        'end_time',
        'status',
    ];

    public function foods()
    {
        return $this->hasMany(Food::class);
    }
}
