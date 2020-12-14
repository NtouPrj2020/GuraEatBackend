<?php

namespace App\Http\Controllers;

use App\Models\DeliveryMan;
use Illuminate\Http\Request;

class GeographyController extends Controller
{
    public function editDeliveryManLocation(Request $request)
    {
        # code...
        $request->validate([
            'longitude' => 'required',
            'latitude' => 'required',
        ]);
        $DeliveryManLocation = $request ->user();

        if ($DeliveryManLocation != null)
        {
            $DeliveryManLocation->longitude=$request->longitude;
            $DeliveryManLocation->latitude=$request->latitude;
            $DeliveryManLocation->save();
            $data = [
                        "status" => 200,
                        "method" => "editDeliveryManLocation",
                        "message" => "sucess",
                        "data" =>[
                            "longitude"=> $request->longitude,
                            "latitude"=> $request->latitude
                        ]
                    ];
                    return response()->json($data, 200);
        }
        else
        {
            $data = [
                        "status" => 403,
                        "method" => "getDeliveryManLocation",
                        "message" => "user not found",
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
                        "data" =>[
                            "longitude"=>127.30389378,
                            "latitude"=>36.30389378
                        ]
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
    public function getLocationByDeliveryManID(Request $request)
    {
        # code...
        $customer = $request ->user();
        $request->validate([
            'deliveryman_id' => 'required',
        ]);
        $deliveryman = DeliveryMan::where('id', '=', $request->deliveryman_id)->first();
        if ($deliveryman != null)
        {
            $data = [
                "status" => 200,
                "method" => "getLocationByDeliveryManID",
                "message" => "sucess",
                "longitude" => $deliveryman->longitude,
                "latitude"=> $deliveryman->latitude,
            ];
            return response()->json($data, 200);
        }
        else
        {
            $data = [
                "status" => 403,
                "method" => "getLocationByDeliveryManID",
                "message" => "user not found",
                "longitude" => "",
                "latitude" => ""
            ];
            return response()->json($data,403);
        }
    }
}
