<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Restaurant extends Authenticatable
{
    use HasFactory,HasApiTokens;

    protected $table = 'Restaurants';

    protected $fillable = [
        'phone',
        'email',
        'name',
        'address',
        'img'
    ];

    protected $hidden = [
        'password'
    ];

    public function tags()
    {
        return $this->belongsToMany('App\Models\RestaurantTag','restaurant_id');
    }

    public function dishes()
    {
        return $this->hasMany('App\Models\Dishes','restaurant_id');
    }

}
