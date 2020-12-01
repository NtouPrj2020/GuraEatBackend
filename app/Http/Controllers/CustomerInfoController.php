<?php

namespace App\Http\Controllers;

use App\Models\Customer;

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
}

?>
