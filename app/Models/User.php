<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
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
        'nid',
        'nid_image',
        'trade_licence',
        'visiting_card'
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

    public function getImage()
    {
        if ($this->image) {
            return asset('nid_images/69460d36524751766198582.jpg' . $this->image);
        }

        return asset('images/default-food.png');
    }

    public function getAvaterAttribute($value)
    {
        return $value ? asset($value) : asset('backend/assets/img/default-avatar.png');
    }

    public function getNidImageAttribute($value)
    {
        return $value ? asset($value) : asset('backend/assets/img/default-file.png');
    }

    public function getTradeLicenceAttribute($value)
    {
        return $value ? asset($value) : asset('backend/assets/img/default-file.png');
    }

    public function getVisitingCardAttribute($value)
    {
        return $value ? asset($value) : asset('backend/assets/img/default-file.png');
    }
}
