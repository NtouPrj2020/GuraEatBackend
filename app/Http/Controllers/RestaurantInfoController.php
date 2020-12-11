<?php

namespace App\Http\Controllers;

use App\Models\RestaurantTag;
use App\Models\RestaurantRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RestaurantInfoController extends Controller
{
    public function getRestaurant(Request $request){

        $restaurant = $request->user();
        $restaurant['tags'] = $restaurant->tags()->get();
        $rate = $restaurant->rates()->first();
        if ($rate!=null) {
            $restaurants['avg_rate'] = CustomerGetRestaurantInfoController::count_avg($restaurant);
            $restaurants['sum_people'] =CustomerGetRestaurantInfoController::sum_people($restaurant);
        }
        if($restaurant != null){
            $data = [
                "status" => 200,
                "method" => "getRestaurant",
                "message" => "success",
                "data" =>$restaurant
            ];
            return response()->json($data, 200);
        }else{
            $data = [
                "status" => 403,
                "method" => "getRestaurant",
                "message" => "user not found",
                "data" => ""
            ];
            return response()->json($data,403);
        }
    }
    public function getAllTags(Request $request){
        $tags = RestaurantTag::all();
        $data = [
            "status" => 200,
            "method" => "getAllTags",
            "message" => "success",
            "data" =>$tags
        ];
        return response()->json($data, 200);
    }
    public function editRestaurant(Request $request){
        $restaurant = $request->user();
        $request->validate([
            'name'=>'required|string',
            'address'=>'required|string',
            'img'=>'required|string',
            'phone'=>'required|string',
            'tags' => 'required'
        ]);
        if($restaurant != null){
            $restaurant->name = $request->name;
            $restaurant->address = $request->address;
            $restaurant->img = $request->img;
            $restaurant->phone = $request->phone;
            $restaurant->save();
            DB::table('RestaurantTags_xref')->where('restaurant_id', '=', $restaurant->id)->delete();
            $tags = $request->tags;
            for ($i = 0; $i < count($tags); $i++) {
                $tag = RestaurantTag::find($tags[$i]);
                if($tag != null){
                    $restaurant->tags()->save($tag);
                }
            }
            $restaurant['tags'] = $restaurant->tags()->get();
            $data = [
                "status" => 200,
                "method" => "editRestaurant",
                "message" => "success",
                "data" =>$restaurant
            ];
            return response()->json($data, 200);
        }else{
            $data = [
                "status" => 403,
                "method" => "editRestaurant",
                "message" => "user not found",
                "data" => ""
            ];
            return response()->json($data,403);
        }
    }
}
