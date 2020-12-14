<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function getAllOrders(Request $request)
    {
        $customer = $request->user();
        $orderlist = $customer->orders()->get();
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
        $customer = $request->user();
        $order = Order::where("customer_id", "=", $customer->id)->find($request->id);
        if ($order != null) {
            $data = [
                "method" => "getOrderByID",
                "message" => "ok",
                "status" => 200,
                "data" => $order->first()
            ];
            return response()->json($data, 200);
        } else {
            $data = [
                "method" => "getOrderByID",
                "message" => "not found",
                "status" => 404,
                "data" => []
            ];
            return response()->json($data, 404);
        }
    }
        public function customergetOrdernow(Request $request)
    {
        $customer = $request->user();
        $order = Order::where([['status', '<', 3],["customer_id","=",$customer->id]])->get();
        if(count($order)!=0){
            $data = [
                "method" => "customergetOrdernow",
                "message" => "ok",
                "status" => 200,
                "data" => $order
            ];
            return response()->json($data, 200);
        }else{
            $data = [
                "method" => "customergetOrdernow",
                "message" => "not found",
                "status" => 404,
                "data" => []
            ];
            return response()->json($data, 404);
        }
    }

}
