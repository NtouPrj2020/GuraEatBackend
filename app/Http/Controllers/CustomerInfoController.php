<?php

namespace App\Http\Controllers;

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
                        "method" => "customerLogout",
                        "message" => "user not found",
                        "data" => ""
                    ];

                    return response()->json($data,403);
        }
    }

    public function editCustomer(Request $request)
    {
        $customer = $request->user();
        $request->validate([
            'ID' => 'required|string',
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $customer = User::findOrFail($request->ID);
        $customer->name=$request->name;
        $customer->password=$request->password;
        $customer->email=$request->email;
        $customer->customer_id=$request->id;
        $customer->save();
        ///
    }
    public function deleteCustomer(Request $request)
    {
        $customer = $request->user();
        $request->validate([
            'ID' => 'required|string',
        ]);
        $customer = User::findOrFail($request->ID);
        $customer->delete();
    }
}

?>
