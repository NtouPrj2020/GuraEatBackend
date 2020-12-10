<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryManRate extends Model
{
    use HasFactory;
    protected $table = 'deliverymanrate';

    protected $fillable = [
        'star5',
        'star4',    
        'star3',
        'star2',
        'star1'
    ];
    public function deliveryman()
    {
        return $this->hasOne('App\Models\DeliveryMan','delivery_man_id');
    }}
