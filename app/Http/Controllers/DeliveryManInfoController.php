<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DeliveryManInfoController extends Controller
{
    public function getDeliveryMan(Request $request)
    {
        $delivery_man = $request->user();
        $delivery_man['rate'] = $delivery_man->rate()->get();

        $star5 = $delivery_man['rate'][0]->star5;
        $star4 = $delivery_man['rate'][0]->star4;
        $star3 = $delivery_man['rate'][0]->star3;
        $star2 = $delivery_man['rate'][0]->star2;
        $star1 = $delivery_man['rate'][0]->star1;
        $avg= ($star5 *5+ $star4 *4+ $star3 *3+ $star2 *2+ $star1)/($star5 + $star4 + $star3 + $star2 + $star1);
        $avg = round($avg,2);
        $delivery_man['rate'][0]['avg_rate'] = $avg;

        if ($delivery_man != null)
        {
            $data = [
                "status" => 200,
                "method" => "getDeliveryMan",
                "message" => "success",
                "data" =>$delivery_man
            ];
            return response()->json($data, 200);
        }
        else
        {
            $data = [
                "status" => 403,
                "method" => "getDeliveryMan",
                "message" => "user not found",
                "data" => ""
            ];

            return response()->json($data,403);
        }
    }
    public function editDeliveryMan(Request $request)
    {
        $request->validate([
            'name'=>'required|string',
            'license_id'=>'required|string',
            'phone'=>'required'
        ]);
        $delivery_man = $request->user();

        if ($delivery_man != null)
        {
            $delivery_man->name = $request->name;
            $delivery_man->license_id = $request->license_id;
            $delivery_man->phone = $request->phone;
            $delivery_man->save();
            $data = [
                "status" => 200,
                "method" => "editDeliveryMan",
                "message" => "success",
                "data" =>$delivery_man
            ];
            return response()->json($data, 200);
        }
        else
        {
            $data = [
                "status" => 403,
                "method" => "editDeliveryMan",
                "message" => "user not found",
                "data" => $delivery_man
            ];

            return response()->json($data,403);
        }
    }
}
