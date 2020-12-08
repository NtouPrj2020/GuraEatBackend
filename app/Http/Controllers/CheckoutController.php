<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;


class CheckoutController extends Controller
{
    public function addressToLocation(Request  $request){
        $request->validate([
            'address' => 'required|string',
        ]);
        $client = new Client(['base_uri' => 'https://maps.googleapis.com','timeout'  => 2.0,]);
        $response = $client->get('/maps/api/geocode/json',[
            'query'=>[
                'address' => $request->address,
                'key'=>$_ENV['GOOGLE_MAP_API']
            ]
        ]);
        $object = json_decode($response->getBody());
        $data = [
            "status" => 200,
            "method" => "addressToLocation",
            "message" => "success",
            "data"=> $object
        ];
        return response()->json($data, 200);
    }
}
