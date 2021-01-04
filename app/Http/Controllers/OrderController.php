<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\DeliveryMan;
use App\Models\Dish;
use App\Models\Order;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class OrderController extends Controller
{
 public static function addItemForOrder(Order $order)
 {
  $items = [];
  for ($j = 0; $j < count($order->items()->get()); $j++) {
   $it   = $order->items()->get()[$j];
   $dish = Dish::find($it->dish_id);
   if ($dish != null) {
    $items[$j]['id']          = $dish->id;
    $items[$j]['name']        = $dish->name;
    $items[$j]['making_time'] = $dish->making_time;
    $items[$j]['img']         = $dish->img;
    $items[$j]['amount']      = $order->items()->get()[$j]['amount'];
   }
  }
  $order['items']       = $items;
  $restaurant           = Restaurant::find($order->restaurant_id);
  $order['restaurant']  = $restaurant;
  $deliveryMan          = DeliveryMan::find($order->delivery_man_id);
  $order['deliveryMan'] = $deliveryMan;
  $customer             = Customer::find($order->customer_id);
  $order['customer']    = $customer;
 }

 public function getAllOrders(Request $request)
 {
  $customer  = $request->user();
  $orderlist = $customer->orders()->get();
  for ($i = 0; $i < count($orderlist); $i++) {
   $this->addItemForOrder($orderlist[$i]);
  }
  $data = [
   "method"  => "getAllOrders",
   "message" => "ok",
   "status"  => 200,
   "data"    => [
    'order' => $orderlist,
   ],
  ];
  return response()->json($data, 200);
 }
 public function getOrderByID(Request $request)
 {
  $request->validate([
   'id' => 'required']);

  $customer = $request->user();
  $order    = Order::where("customer_id", "=", $customer->id)->find($request->id)->first();
  if ($order != null) {
   $this->addItemForOrder($order);
   $data = [
    "method"  => "getOrderByID",
    "message" => "ok",
    "status"  => 200,
    "data"    => [
     'order' => $order,
    ],
   ];
   return response()->json($data, 200);
  } else {
   $data = [
    "method"  => "getOrderByID",
    "message" => "not found",
    "status"  => 404,
    "data"    => [],
   ];
   return response()->json($data, 404);
  }
 }
 public function customergetOrdernow(Request $request)
 {
  $customer = $request->user();
  $order    = Order::where([['status', '<', 4], ["customer_id", "=", $customer->id]])->first();
  if ($order != null) {
   $this->addItemForOrder($order);
   $data = [
    "method"  => "customergetOrdernow",
    "message" => "ok",
    "status"  => 200,
    "data"    => $order,
   ];
   return response()->json($data, 200);
  } else {
   $data = [
    "method"  => "customergetOrdernow",
    "message" => "not found",
    "status"  => 404,
    "data"    => [],
   ];
   return response()->json($data, 404);
  }
 }

}
