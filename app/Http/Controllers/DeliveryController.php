<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    public function getAllOrders(Request $request)
    {
        $delivery = $request->user();
        $orderlist = $delivery->orders()->get();
        $data = [
            "method" => "getAllOrders",
            "message" => "ok",
            "status" => 200,
            "data" => $orderlist
        ];
        return response()->json($data, 200);
    }
    public function getOrderByID(Request $request)
    {
        $request->validate([
            'id' => 'required',]);
        $delivery = $request->user();
        $order = Order::where("delivery_man_id","=",$delivery->id)->find($request->id);
        if($order!=null){
            $data = [
                "method" => "getOrderByID",
                "message" => "ok",
                "status" => 200,
                "data" => $order->first()
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
        $order = Order::where([['status', '<', 3],["delivery_man_id","=",$deliveryman->id]])->get();
        if(count($order)!=0){
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
}
