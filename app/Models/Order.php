<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'Orders';
    
    protected $fillable = [
        'status',
        'type',    
        'distance',
        'send_time'
    ];

    protected $guarded = ['status'];

    public function delivery_man()
    {
        return $this->belongsTo('App\Models\Customer','customer_id');
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\DeliveryMan','delivery_man_id');
    }

}
