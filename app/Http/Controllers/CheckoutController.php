<?php

namespace App\Http\Controllers;

use App\Models\DeliveryMan;
use App\Models\Order;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Exception;

class CheckoutController extends Controller
{

    public function addressToLocation(Request $request)
    {
        $request->validate([
            'address' => 'required|string',
        ]);
        $res = $this->addtolo($request->address);
        $data = json_decode($res->getBody());
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
        $client = new Client(['base_uri' => 'https://maps.googleapis.com', 'timeout' => 2.0,]);
        $res = $client->post('/maps/api/geocode/json', ['query' => [
            'address' => $address,
            'key' => $_ENV['GOOGLE_MAP_API']
        ]
        ]);
        $data = json_decode($res->getBody());
        return $data;
    }

    public function loToAdd($long, $lat)
    {
        $client = new Client(['base_uri' => 'https://maps.googleapis.com', 'timeout' => 2.0,]);
        $res = $client->get('/maps/api/geocode/json', ['query' => [
            'latlng' => $lat . ',' . $long,
            'key' => $_ENV['GOOGLE_MAP_API'],
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
            $base_url = "https://maps.googleapis.com";
            $url = "/maps/api/distancematrix/json?origins={$request->ori_address}&destinations={$request->des_address}&key={$_ENV['GOOGLE_MAP_API']}";
            $client = new Client([
                // Base URI is used with relative requests
                'base_uri' => $base_url,
                // You can set any number of default request options.
                'timeout' => 2.0,
            ]);
            $res = $client->request('GET', $url);
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
        /*$request->validate([
            'restaurant_id' => 'required',
            'menu' => 'required',]);*/
        $customer = $request->user();
        $onlineDeliveryMans = DeliveryMan::where('status', '=', '1')->get();

        if (count($onlineDeliveryMans) > 0) {
            $restaurant = Restaurant::find($request->restaurant_id)->first();
            $res = $this->addtolo($restaurant->address);
            $obj = json_decode($res->getBody());
            $rest_lat = $obj->results[0]->geometry->location->lat;
            $rest_lng = $obj->results[0]->geometry->location->lng;
            $distanceList = [];
            for ($i = 0; $i < count($onlineDeliveryMans); $i++) {
                array_push(
                    $distanceList,
                    $this->getDistance($onlineDeliveryMans[$i]->latitude, $onlineDeliveryMans[$i]->longitude, $rest_lat, $rest_lng)
                );
            }
            $closes = 0;
            for ($i = 0; $i < count($distanceList); $i++) {
                if($distanceList[$i]>$distanceList[$closes]){
                    $closes = $i;
                }
            }
            $chooseMan = $onlineDeliveryMans[$closes];
            $order = new Order();
            $order->delivery_man_id = $chooseMan->id;
            $order->customer_id = $customer->id;
            if($request->type == 0 ){
                $order->status = 1;
            }else{
                $order->status = 0;
            }
            $order->type = $request->type;
            $distance = $distanceList[$closes];
            $send_time = $request->send_time;
        } else {

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
