<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'Customers';

    protected $fillable = [
        'phone',
        'email',
        'name',
        'address'
    ];

    protected $hidden = [
        'password'
    ];

    public function orders()
    {
        return $this->hasMany('App\Models\Order','customer_id');
    }

    public function cart()
    {
        return $this->hasMany('App\Models\Cart','customer_id');
    }

}
