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
        $client = new Client(['base_uri' => 'https://maps.googleapis.com/maps/api/','timeout'  => 2.0,]);
        $response = $client->post('https://maps.googleapis.com/maps/api/post',[
            'address' => $request->address,
            'key'=>GOOGLE_MAP_API
        ]);
        $data = $response->getBody();
        return response()->json($data, 200);
    }
}
