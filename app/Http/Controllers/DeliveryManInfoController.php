<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DeliveryManInfoController extends Controller
{
    public function getDeliveryMan(Request $request)
    {
        $delivery_man = $request->user();
        $delivery_man['rate'] = $delivery_man->rate()->get();
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
