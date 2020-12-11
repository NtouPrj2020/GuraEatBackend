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
        $restaurant['rate'] = $restaurant->Rates()->get();

        $star5 = $restaurant['rate'][0]->star5;
        $star4 = $restaurant['rate'][0]->star4;
        $star3 = $restaurant['rate'][0]->star3;
        $star2 = $restaurant['rate'][0]->star2;
        $star1 = $restaurant['rate'][0]->star1;
        $avg= ($star5 *5+ $star4 *4+ $star3 *3+ $star2 *2+ $star1)/($star5 + $star4 + $star3 + $star2 + $star1);    
        $avg = round($avg,2);
        $restaurant['rate'][0]['avg_rate'] = $avg;
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
