<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantRate extends Model
{
    use HasFactory;
    protected $table = 'RestaurantRates';

    protected $fillable = [
        'star5',
        'star4',    
        'star3',
        'star2',
        'star1'
    ];
    public function restaurant()
    {
        return $this->hasOne('App\Models\Restaurant','restaurant_id');
    }
}
