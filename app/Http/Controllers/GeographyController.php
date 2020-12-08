<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GeographyController extends Controller
{
    public function editDeliveryManLocation(Request $request)
    {
        # code...
        $request->validate([
            'longitude' => 'required',
            'latitude' => 'required',
            'token' => 'required',
        ]);
        $DeliveryManLocation = $request ->user();

        if ($DeliveryManLocation != null)
        {
            $DeliveryManLocation->longitude=$request->longitude;
            $DeliveryManLocation->latitude=$request->latitude;
            $DeliveryManLocation->save();
            $data = [
                        "status" => 200,
                        "method" => "getDeliveryManLocation",
                        "message" => "sucess",
                        "data" =>$DeliveryManLocation
                    ];
                    return response()->json($data, 200);
        }
        else
        {
            $data = [
                        "status" => 403,
                        "method" => "getDeliveryManLocation",
                        "message" => "user not found",
                        "data" => ""
                    ];

                    return response()->json($data,403);
        }
    }
    public function getDeliveryManLocation(Request $request)
    {
        # code...
        $DeliveryManLocation = $request ->user();
        
        if ($DeliveryManLocation != null)
        {
            $data = [
                        "status" => 200,
                        "method" => "getDeliveryManLocation",
                        "message" => "sucess",
                        "data" =>$DeliveryManLocation
                    ];
                    return response()->json($data, 200);
        }
        else
        {
            $data = [
                        "status" => 403,
                        "method" => "getDeliveryManLocation",
                        "message" => "user not found",
                        "data" => ""
                    ];

                    return response()->json($data,403);
        }
    }
}
