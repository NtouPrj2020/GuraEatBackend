<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryMan extends Model
{
    use HasFactory;

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
