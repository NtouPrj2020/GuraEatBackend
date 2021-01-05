<?php

namespace App\Http\Controllers;

use App\Models\DeliveryMan;
use App\Models\RestaurantRate;
use App\Models\DeliveryManRate;
use App\Models\Order;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Return_;

class confirmOrderController extends Controller
{
    public function addStar($Rate,$star)
    {
        # code...
        switch ($star) {
            case '1':
                # code...
                $Rate->star1 +=1;
                break;
            case '2':
                # code...
                $Rate->star2 +=1;
                break;
            case '3':
                # code...
                $Rate->star3 +=1;
                break;
            case '4':
                # code...
                $Rate->star4 +=1;
                break;
            case '5':
                # code...
                $Rate->star5 +=1;
                break;
            default:
                # code...
                break;
        }
        $Rate->save();
        return $Rate;
    }
    public function rateOrder(Request $request)
    {
        $customer = $request->user();
        $request->validate([
            'delivery_man_star' => 'required|int',
            'restaurant_star' => 'required|int',
            'order_id' => 'required'
        ]);
        if($customer!=null)
        {
            $order_id = $request->order_id;
            $finishedOrder = $customer->orders()->where('id','=',$order_id)->first();
            $chooseMan = $finishedOrder->delivery_man_id;
            $DeliveryManRate = DeliveryManRate::where('delivery_man_id','=',$chooseMan)->first();
            $DeliveryManRate = DeliveryManRate::findorfail($DeliveryManRate->id);
            $chooseRestaurant = $finishedOrder->restaurant_id;
            $RestaurantRate = RestaurantRate::where('restaurant_id','=',$chooseRestaurant)->first();
            $RestaurantRate = RestaurantRate::findorfail($RestaurantRate->id);        
            $Rate = $DeliveryManRate;
            $star = $request->delivery_man_star;
            $AfterDeliveryManRate = $this->addStar($Rate,$star);
            $Rate = $RestaurantRate;
            $star = $request->restaurant_star;
            $AfterRestaurantRate = $this->addStar($Rate,$star);
            $customer['restaurantRate'] = $request->restaurant_star;
            $customer['deliveryManRate'] = $request->delivery_man_star;
            $customer['restaurant_id'] =$chooseRestaurant;
            $customer['delivery_man_id'] =$chooseMan;
            $customer['order_status'] = 4;
            if($AfterDeliveryManRate->getChanges() != null)
            {
                $data = [
                    "status" => 200,
                    "method" => "rateOrder",
                    "message" => "ok",
                    "data" => $customer
                ];
                return response()->json($data, 200);
            }else{
                $data = [
                    "status" => 404,
                    "method" => "rateOrder",
                    "message" => "錯誤",
                    "data" => ""
                ];
            return response()->json($data, 404);
            };
        }else {
            $data = [
                "status" => 403,
                "method" => "rateOrder",
                "message" => "認證錯誤",
                "data" => ""
            ];
            return response()->json($data, 403);
        }
    }
}
