<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'Cart';

    protected $fillable = [
        'id',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\Customer','customer_id');
    }

    public function dish()
    {
        return $this->hasOne('App\Models\Dish','dish_id');
    }

}
