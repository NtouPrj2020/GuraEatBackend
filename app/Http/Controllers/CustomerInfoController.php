<?php

namespace App\Http\Controllers;

use App\Models\Customer;

use Illuminate\Http\Request;

class CustomerInfoController extends Controller
{
    public function getCustomer(Request $request)
    {
<<<<<<< HEAD
        $request->validate([
            'customer_id' => 'required',
        ]);

        $customer = Customer:: where("id=",$request->customer_id);
        
=======
        $customer = $request->user();

>>>>>>> 478cc4bf7053c46aa18c1fe6339d774a7d10b465
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
