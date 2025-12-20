<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';

    protected $fillable = [
        'site_name',
        'site_description',
        'footer_text',
        'logo',
        'favicon',
        'email',
        'phone',
        'address',
        'website',
        'facebook',
        'instagram',
        'twitter',
        'linkedin',
    ];

    protected $casts = [];
}
