<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerPasswordReset extends Model
{
    protected $table = 'customer_pwd_resets';
    protected $fillable = [
        'email', 'token'
    ];
}
