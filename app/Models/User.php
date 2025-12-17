<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'phone',
        'password',
        'employee_number',
        'team_id',
        'floor',
        'row',
        'seat_number',
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function todayMeals()
    {
        return $this->hasManyThrough(TodayMeal::class, Food::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function vendorOrders()
    {
        return $this->hasMany(Order::class, 'vendor_id');
    }

    public function foods()
    {
        return $this->hasMany(Food::class);
    }
}
