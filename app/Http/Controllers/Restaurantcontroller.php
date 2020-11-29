<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Restaurant;

class Restaurantcontroller extends Controller
{
    public function restaurants(Request $request){
        $restaurants = Restaurant::paginate(20);
        $data = [
            "status" => 401,
            "method" => "customerLogin",
            "message" => "failed",
            "data" => $restaurants
        ];
        return response()->json($data, 401);
    }
}
