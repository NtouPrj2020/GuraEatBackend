<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DeliveryManChangeStateController extends Controller
{
    public function changeState(Request $request){
        $request->validate([
            'status' => 'required',
        ]);
        $man = $request->user();
        $man->status = $request->status;
        $man->save();
        if ($man != null)
        {
            $data = [
                "status" => 200,
                "method" => "changeState",
                "message" => "success",
                "data" =>$man
            ];
            return response()->json($data, 200);
        }
        else
        {
            $data = [
                "status" => 403,
                "method" => "changeState",
                "message" => "user not found",
                "data" => ""
            ];

            return response()->json($data,403);
        }
    }
}
