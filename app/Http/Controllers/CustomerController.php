<?php

namespace App\Http\Controllers;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function customerAccount(Request $request)
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
