<?php
namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class DeliveryManGetOrderEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $deliveryMan;

    public function __construct($deliveryMan,$message)
    {
        $this->deliveryMan = $deliveryMan;
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return ['deliveryman.getorder.'.$this->deliveryMan->id];
    }

    public function broadcastAs()
    {
        return '.deliveryman.getorder';
    }
}
