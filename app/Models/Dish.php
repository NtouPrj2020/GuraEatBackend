<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dish extends Model
{
    use HasFactory;

    protected $table = 'Dishes';

    protected $fillable = [
        'name',
        'price',    
        'img',
        'making_time'
    ];

    public function restaurant()
    {
        return $this->hasOne('App\Models\Restaurant','restaurant_id');
    }
}
