<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerInfoController extends Controller
{
    public function getCustomer(Request $request)
    {
        $customer = $request->user();

        if ($customer != null)
        {
            $data = [
                        "status" => 200,
                        "method" => "getCustomer",
                        "message" => "sucess",
                        "data" =>$customer
                    ];
                    return response()->json($data, 200);
        }
        else
        {
            $data = [
                        "status" => 403,
                        "method" => "getCustomer",
                        "message" => "user not found",
                        "data" => ""
                    ];

                    return response()->json($data,403);
        }
    }

    public function editCustomer(Request $request)
    {
        //dd($request);
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'address' => 'required',
        ]);
        $customer = $request->user();
        if ($customer != null)
        {
            $customer->phone=$request->phone;
            $customer->email=$request->email;
            $customer->name=$request->name;
            $customer->address=$request->address;
            $customer->save();
            $data = [
                        "status" => 200,
                        "method" => "editCustomer",
                        "message" => "sucess",
                    ];
                    return response()->json($data, 200);
        }
        else
        {
            $data = [
                        "status" => 403,
                        "method" => "editCustomer",
                        "message" => "user not found",
                    ];

                    return response()->json($data,403);
        }
    }
}

?>
