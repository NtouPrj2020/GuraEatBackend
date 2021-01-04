<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use App\Models\Order;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    public function getAllOrders(Request $request)
    {
        $delivery = $request->user();
        $orderlist = $delivery->orders()->get();
        for($i = 0;$i<count($orderlist);$i++){
           OrderController::addItemForOrder($orderlist[$i]);
        }
        $data = [
            "method" => "getAllOrders",
            "message" => "ok",
            "status" => 200,
            "data" => [
                'order'=>$orderlist,
            ]
        ];
        return response()->json($data, 200);
    }
    public function getOrderByID(Request $request)
    {
        $request->validate([
            'status' => 'required',]);
        $delivery = $request->user();
        $order = Order::where("delivery_man_id","=",$delivery->id)->find($request->id)->first();
        if($order!=null){
            OrderController::addItemForOrder($order);
            $data = [
                "method" => "getOrderByID",
                "message" => "ok",
                "status" => 200,
                "data" => [
                    'order'=>$order,
                    'items'=>$order->items()
                ]
            ];
            return response()->json($data, 200);
        }else{
            $data = [
                "method" => "getOrderByID",
                "message" => "not found",
                "status" => 404,
                "data" => []
            ];
            return response()->json($data, 404);
        }
    }
    public function deliverymangetOrdernow(Request $request)
    {
        $deliveryman = $request->user();
        $order = Order::where([['status', '<', 3],["delivery_man_id","=",$deliveryman->id]])->first();
        if($order!=null){
            OrderController::addItemForOrder($order);
            $data = [
                "method" => "deliverymangetOrdernow",
                "message" => "ok",
                "status" => 200,
                "data" => $order
            ];
            return response()->json($data, 200);
        }else{
            $data = [
                "method" => "deliverymangetOrdernow",
                "message" => "not found",
                "status" => 404,
                "data" => []
            ];
            return response()->json($data, 404);
        }
    }
    public function deliveryManChangeOrderStatus(Request $request)
    {
        $request->validate([
            'stauts' => 'required',]);
        $order = Order::where('id','=',$request->id)->first();
        if($order!=null){
            $order->stauts = $request->stauts;
            $data = [
                "method" => "deliveryManChangeOrderStatus",
                "message" => "ok",
                "status" => 200,
                "data" => $order
            ];
            return response()->json($data, 200);
        }else{
            $data = [
                "method" => "deliveryManChangeOrderStatus",
                "message" => "not found",
                "status" => 404,
                "data" => []
            ];
            return response()->json($data, 404);
        }
    }
}
