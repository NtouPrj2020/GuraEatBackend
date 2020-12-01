<?php

namespace App\Http\Controllers;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerInfoController extends Controller
{
    public function getCustomer(Request $request)
    {
        $validatedata = $request->validate([
            'customer_id' => 'required',
        ]);

        $customer = Customer:: where("id=",$request->customer_id);
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
                        "data" =>null
                    ];
                    
                    return response()->json($data,403);
        }
        
        
        
    }
    /*
    public function editCustomer(Request $request)
    {
        $request->validate([
            'customer_id' => 'required',
            'authorization' => 'required',
        ]);
        $customer = Customer:: where("id=",$request->customer_id);
        
        if ($customer != null)
        {
            $data = [
                        "status" => 200,
                        "method" => "editCustomer",
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
                        "data" =>null
                    ];
                    
                    return response()->json($data,403);
        }
    }
    public function deleteCustomer(Request $request)
    {
        $request->validate([
            'customer_id' => 'required',
            'authorization' => 'required',
        ]);
        $customer = Customer:: where("id=",$request->customer_id);
        
        if ($customer != null)
        {
            $data = [
                        "status" => 200,
                        "method" => "editCustomer",
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
                        "data" =>null
                    ];
                    
                    return response()->json($data,403);
        }
    }
    */
}

?>
