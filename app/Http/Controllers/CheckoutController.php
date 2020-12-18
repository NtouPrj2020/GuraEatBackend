<?php

namespace App\Http\Controllers;

use App\Events\DeliveryManGetOrderEvent;
use App\Models\Cart;
use App\Models\DeliveryMan;
use App\Models\Order;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Exception;
use Illuminate\Support\Carbon;

class CheckoutController extends Controller
{

    public function addressToLocation(Request $request)
    {
        $request->validate([
            'address' => 'required|string',
        ]);
        $data = $this->addtolo($request->address);
        $data = json_decode($data);
        return response()->json($data, 200);
    }

    public function locationToAddress(Request $request)
    {
        $request->validate([
            'long' => 'required',
            'lat' => 'required',
        ]);
        $res = $this->loToAdd($request->long, $request->lat);
        if ($res->getStatusCode() == 400) {
            $output = [
                "method" => "locationToAddress",
                "message" => "long and lat is invalid",
                "status" => 400,
                "data" => []
            ];
            return response()->json($output, 400);
        } else {
            $data = json_decode($res->getBody());
            $output = [
                "method" => "locationToAddress",
                "message" => "ok",
                "status" => 200,
                "data" => $data->results[0]->formatted_address
            ];
            return response()->json($output, 200);
        }
    }

    public function addtolo(string $address)
    {
        $client = new Client(['base_uri' => 'https://maps.googleapis.com', 'timeout' => 20.0,]);
        $res = $client->post('/maps/api/geocode/json', ['query' => [
            'address' => $address,
            'key' => env('GOOGLE_MAP_API')
        ]
        ]);

        return $res->getBody();
    }

    public function loToAdd($long, $lat)
    {
        $client = new Client(['base_uri' => 'https://maps.googleapis.com', 'timeout' => 20.0,]);
        $res = $client->get('/maps/api/geocode/json', ['query' => [
            'latlng' => $lat . ',' . $long,
            'key' => env('GOOGLE_MAP_API'),
            'language' => 'zh-TW'
        ]
        ]);
        return $res;
    }

    public function getDistanceAndTimeByAddress(Request $request)
    {
        $request->validate([
            'ori_address' => 'required',
            'des_address' => 'required',]);

        try {
            $client = new Client(['base_uri' => 'https://maps.googleapis.com', 'timeout' => 20.0,]);
            $res = $client->get('/maps/api/distancematrix/json', ['query' => [
                'origins' => $request->ori_address,
                'destinations' => $request->des_address,
                'key' => env('GOOGLE_MAP_API')
            ]]);
            $dd = json_decode($res->getBody());
            $data = [
                "method" => "getDistanceAndTimeByAddress",
                "message" => "ok",
                "status" => 200,
                "data" => $dd
            ];
            return response()->json($data, 200);
        } catch (Exception $e) {
            $data = [
                "method" => "getDistanceAndTimeByAddress",
                "message" => $e->getMessage(),
            ];
            return response()->json($data, 500);
        }
    }

    public function checkoutAuto(Request $request)
    {
        $req = json_decode($request->getContent());
        $customer = $request->user();
        $onlineDeliveryMans = DeliveryMan::where('status', '=', '1')->get();

        //檢查是否使用者已經有進行中的訂單
        $orderlist = $customer->orders()->get();
        for($i=0;$i<count($orderlist);$i++){
            if( $orderlist[$i]->status == 0 | $orderlist[$i]->status == 1 || $orderlist[$i]->status == 2){
                $data = [
                    "method" => "checkoutAuto",
                    "message" => "already have order",
                    "status" => 4033,
                    "data" => [
                        'order'=>$orderlist[$i],
                        'items'=>$orderlist[$i]->items()
                    ]
                ];
                return response()->json($data, 403);
            }
        }


        //檢查是否有外送員上線中
        if (count($onlineDeliveryMans) > 0) {
            $restaurant = Restaurant::find($req->restaurant_id);
            $res = $this->addtolo($restaurant->address);
            $obj = json_decode($res);
            if(count($obj->results) == 0){
                $data = [
                    "method" => "checkoutAutogoog",
                    "message" => "google api key expired or zero result",
                    "status" => 500,
                    "data" => [
                    ]
                ];
                return response()->json($data, 500);
            }
            $rest_lat = $obj->results[0]->geometry->location->lat;
            $rest_lng = $obj->results[0]->geometry->location->lng;
            //計算離餐廳最近的上線中外送員
            $distanceList = [];
            $manList = [];

            for ($i = 0; $i < count($onlineDeliveryMans); $i++) {
                $onlineDeliveryMans[$i]->distance = $this->getDistance($onlineDeliveryMans[$i]->latitude, $onlineDeliveryMans[$i]->longitude, $rest_lat, $rest_lng);
            }
            $chooseMan = null;
            $onlineDeliveryMans = $onlineDeliveryMans->sortBy('distance');
            $chooseMan = $this->getChooseMan($onlineDeliveryMans);
            if($chooseMan==null){
                $data = [
                    "method" => "checkoutAuto",
                    "message" => "no delivery man is available",
                    "status" => 4032,
                    "data" => [
                    ]
                ];
                return response()->json($data, 403);
            }
            //建立訂單
            $order = new Order();
            $order->restaurant_id = $req->restaurant_id;
            $order->delivery_man_id = $chooseMan->id;
            $order->customer_id = $customer->id;
            $order->delivery_fee = $req->delivery_fee;
            $order->food_price = $req->food_price;
            $order->type = $req->type;
            $order->note = $req->note;
            $order->customer_address = $req->customer_address;
            $order->distance = $chooseMan->distance;
            if($req->type == 0 ){
                $order->status = 1;
            }else if($req->type == 1){
                $order->status = 0;
                $t = Carbon::parse($req->send_time)->addHours(8);
                $t->format('Y-m-d H:i');;
                $order->send_time = $t;
            }
            $order->save();
            //商品和訂單連結
            for($i=0;$i<count($req->menu);$i++){
                $cart = new Cart();
                $cart->dish_id = $req->menu[$i]->id;
                $cart->customer_id = $customer->id;
                $cart->order_id = $order->id;
                $cart->amount = $req->menu[$i]->amount;
                $cart->save();
            }
            $data = [
                "method" => "checkoutAuto",
                "message" => "ok",
                "status" => 201,
                "data" => [
                    'order'=>$order,
                    'items'=>$order->items()->get()
                ]
            ];
            event(new DeliveryManGetOrderEvent($chooseMan,$data));
            return $data;
        } else {
            $data = [
                "method" => "checkoutAuto",
                "message" => "no delivery man is online",
                "status" => 4031,
                "data" => []
            ];
            return response()->json($data, 403);
        }
    }


    function getChooseMan($onlineDeliveryMans){
        $chooseMan = null;
        for($i = 0;$i<count($onlineDeliveryMans);$i++){
            $manOrders = $onlineDeliveryMans[$i]->orders()->get();
            $haveOrder = false;
            for($j = 0;$j<count($manOrders);$j++){
                if( $manOrders[$j]->status < 3){
                    $haveOrder = true;
                }
            }
            if(!$haveOrder){
                $chooseMan = $onlineDeliveryMans[$i];
            }
        }
        if($chooseMan != null){
            return $chooseMan;
        }else{
            return null;
        }
    }

    function getDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6367000; //approximate radius of earth in meters

        $lat1 = ($lat1 * pi()) / 180;
        $lng1 = ($lng1 * pi()) / 180;

        $lat2 = ($lat2 * pi()) / 180;
        $lng2 = ($lng2 * pi()) / 180;

        $calcLongitude = $lng2 - $lng1;
        $calcLatitude = $lat2 - $lat1;
        $stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);
        $stepTwo = 2 * asin(min(1, sqrt($stepOne)));
        $calculatedDistance = $earthRadius * $stepTwo;

        return round($calculatedDistance);
    }
}
