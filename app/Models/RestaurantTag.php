<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantTag extends Model
{
    use HasFactory;

    protected $table = 'RestaurantTags';

    protected $fillable = [
        'name'
    ];

    public function restaurants()
    {
        return $this->belongsToMany('App\Models\Restaurant');
    }
}
