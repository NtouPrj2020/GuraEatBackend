<?php

namespace App\Http\Controllers;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerInfoController extends Controller
{
    public function getCustomer(Request $request)
    {
        $

        $customer=Customer::paginate(20);
        if ($user != null)
        {
            $data = [
                        "status" => 200,
                        "method" => "customerLogout",
                        "message" => "user not found",
                        "data" =>"customerAccount"
                    ];
                    return response()->json($data,200);
        }
        elseif()
        {
            $data = [
                        "status" => 403,
                        "method" => "customerLogout",
                        "message" => "user not found",
                        "data" =>"customerAccount"
                    ];
                    return response()->json($data,403);
        }
        else//
        {
            $data = [
                        "status" => 404,
                        "method" => "customerLogout",
                        "message" => "user not found",
                        "data" =>"customerAccount"
                    ];
                    return response()->json($data,404);
        }
        
        
    }
    public function editCustomer(Request $request)
    {
        $customer=Customer::paginate(20);
        $data = [
            "status" => 403,
            "method" => "customerLogout",
            "message" => "user not found",
            "data" =>"customerAccount"
        ];
        return response();
    }
    public function deleteCustomer(Request $request)
    {
        $customerAccount=Customer::paginate(20);
        $data = [
            "status" => 403,
            "method" => "customerLogout",
            "message" => "user not found",
            "data" =>"customerAccount"
        ];
        return response();
    }
}
?>