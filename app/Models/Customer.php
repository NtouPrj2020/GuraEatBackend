<?php

namespace App\Models;

use App\Notifications\CustomerPasswordResetRequest;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Customer extends Authenticatable
{
    use Notifiable,HasFactory,HasApiTokens;

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

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomerPasswordResetRequest($token));
    }
}
