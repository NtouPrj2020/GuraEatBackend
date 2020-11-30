<?php

namespace App\Http\Controllers;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerInfoController extends Controller
{
    public function getCustomer(Request $request)
    {
        $customerAccount=Customer::paginate(20);
        $data = [
            "status" => 403,
            "method" => "customerLogout",
            "message" => "user not found",
            "data" =>"customerAccount"
        ];
        return response()->json($data,403);
    }
    public function editCustomer(Request $request)
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