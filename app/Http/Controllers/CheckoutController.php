<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Exception;

class CheckoutController extends Controller
{
    public function addressToLocation(Request  $request){
        $request->validate([
            'address' => 'required|string',
        ]);
        $client = new Client(['base_uri' => 'https://maps.googleapis.com/maps/api/','timeout'  => 2.0,]);
        $response = $client->post('https://maps.googleapis.com/maps/api/post',[
            'address' => $request->address,
            'key'=>GOOGLE_MAP_API
        ]);
        $data = $response->getBody();
        return response()->json($data, 200);
    }
    public function getDistanceAndTimeByAddress(Request $request){
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
                'timeout'  => 2.0,
            ]);
            $res = $client->request('GET',$url);
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



}
