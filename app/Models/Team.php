<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = [
        'name',
        'note',
        'status'
    ];


    public function users()
    {
        return $this->hasMany(User::class);
    }
}
