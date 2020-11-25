<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryManPasswordReset extends Model
{
    protected $table = 'delivery_man_pwd_resets';
    protected $fillable = [
        'email', 'token'
    ];
}
