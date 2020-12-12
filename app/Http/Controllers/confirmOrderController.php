<?php

namespace App\Http\Controllers;

use App\Models\DeliveryMan;
use App\Models\RestaurantRate;
use App\Models\DeliveryManRate;
use App\Models\Order;
use Illuminate\Http\Request;

class confirmOrderController extends Controller
{
    //
    public function xx($request)
    {
        # code...
        
    }
    public function rateOrder(Request $request)
    {
        $customer = $request->user();
        $request->validate([//input
            'delivery_man_star' => 'required|int',
            'restaurant_star' => 'required|int',
        ]);
        $finishedOrder = $customer->orders()->where('status','=','2')->where('customer_id','=',$customer->id)->first();
        $chooseMan = $finishedOrder->delivery_man_id;$chooseRestaurant = $finishedOrder->restaurant_id;
        $DeliveryManRate = DeliveryManRate::where('delivery_man_id','=',$chooseMan)->first();
        $restaurantRate = RestaurantRate::where('restaurant_id','=',$chooseRestaurant)->first();
        $DeliveryManRate = DeliveryManRate::findorfail($DeliveryManRate->id);
        $restaurantRate = RestaurantRate::findorfail($restaurantRate->id);
        // dd($restaurantRate);
        switch ($request->delivery_man_star) {
            case '1':
                # code...
                $DeliveryManRate->star1 +=1;
                break;
            case '2':
                # code...
                $DeliveryManRate->star2 +=1;
                break;
            case '3':
                # code...
                $DeliveryManRate->star3 +=1;
                break;
            case '4':
                # code...
                $DeliveryManRate->star4 +=1;
                break;
            case '5':
                # code...
                $DeliveryManRate->star5 +=1;
                break;
            default:
                # code...
                break;
        }
        $DeliveryManRate->save();
        switch ($request->restaurant_star) {
            case '1':
                # code...
                $restaurantRate->star1 +=1;
                break;
            case '2':
                # code...
                $restaurantRate->star2 +=1;
                break;
            case '3':
                # code...
                $restaurantRate->star3 +=1;
                break;
            case '4':
                # code...
                $restaurantRate->star4 +=1;
                break;
            case '5':
                # code...
                $restaurantRate->star5 +=1;
                break;
            default:
                # code...
                break;
        }
        $restaurantRate->save();
        dd($restaurantRate);
        
        # code...

    }
}
