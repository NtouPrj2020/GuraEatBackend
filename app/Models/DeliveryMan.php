<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class DeliveryMan extends Authenticatable
{
    use HasFactory,HasApiTokens;

    protected $table = 'DeliveryMans';

    protected $fillable = [
        'phone',
        'email',
        'name',
        'license_id'
    ];

    protected $hidden = [
        'password'
    ];

    public function orders()
    {
        return $this->hasMany('App\Models\Order','delivery_man_id');
    }

    public function rate()
    {
        return $this->hasOne('App\Models\DeliveryManRate','delivery_man_id');
    }

}
