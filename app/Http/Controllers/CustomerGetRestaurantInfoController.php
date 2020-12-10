<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use App\Models\RestaurantTag;
use Illuminate\Http\Request;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Hash;

class CustomerGetRestaurantInfoController extends Controller
{
    public function getAllRestaurant(Request $request){
        $restaurants = Restaurant::paginate(20);
        for($i = 0;$i<count($restaurants);$i++){
            $restaurants[$i]['tags'] = $restaurants[$i]->tags()->get();
            $restaurants[$i]['rate'] = $restaurants[$i]->Rates()->get();
            $star5 = $restaurants[$i]['rate'][0]->star5;
            $star4 = $restaurants[$i]['rate'][0]->star4;
            $star3 = $restaurants[$i]['rate'][0]->star3;
            $star2 = $restaurants[$i]['rate'][0]->star2;
            $star1 = $restaurants[$i]['rate'][0]->star1;
            $sum_people = ($star5 + $star4 + $star3 + $star2 + $star1);
            $avg= ($star5 *5+ $star4 *4+ $star3 *3+ $star2 *2+ $star1)/$sum_people;    
            $avg = round($avg,2);
            $restaurants[$i]['rate'][0] = ['restaurant_id'=>$restaurants[$i]->id,'avg_rate' => $avg,'sum_people' =>$sum_people];
        }
        $data = [
            "status" => 200,
            "method" => "getAllRestaurant",
            "message" => "success",
            "data"=> $restaurants
        ];
        return response()->json($data, 200);
    }
    public function getRestaurantByID(Request $request){
        $request->validate([
            'ID' => 'required|string',
        ]);
        $restaurants = Restaurant::where('id','=',$request->ID)->first();
        $restaurants['tags'] = $restaurants->tags()->get();
        $restaurants['rate'] = $restaurants->Rates()->get();
            $star5 = $restaurants['rate'][0]->star5;
            $star4 = $restaurants['rate'][0]->star4;
            $star3 = $restaurants['rate'][0]->star3;
            $star2 = $restaurants['rate'][0]->star2;
            $star1 = $restaurants['rate'][0]->star1;
            $sum_people = ($star5 + $star4 + $star3 + $star2 + $star1);
            $avg= ($star5 *5+ $star4 *4+ $star3 *3+ $star2 *2+ $star1)/$sum_people;    
            $avg = round($avg,2);
            $restaurants['rate'][0] = ['restaurant_id'=>$restaurants->id,'avg_rate' => $avg,'sum_people' =>$sum_people];
        $data = [
            "status" => 200,
            "method" => "getRestaurantByID",
            "message" => "success",
            "data"=> $restaurants
        ];
        return response()->json($data, 200);
    }
    public function getRestaurantByKeyword(Request $request){
        $request->validate([
            'Keyword' => 'required|string',
        ]);
        $restaurants = Restaurant::where('name','like','%'.$request->Keyword.'%')->get();
        for($i = 0;$i<count($restaurants);$i++){
            $restaurants[$i]['tags'] = $restaurants[$i]->tags()->get();
            $restaurants[$i]['rate'] = $restaurants[$i]->Rates()->get();
            $star5 = $restaurants[$i]['rate'][0]->star5;
            $star4 = $restaurants[$i]['rate'][0]->star4;
            $star3 = $restaurants[$i]['rate'][0]->star3;
            $star2 = $restaurants[$i]['rate'][0]->star2;
            $star1 = $restaurants[$i]['rate'][0]->star1;
            $sum_people = ($star5 + $star4 + $star3 + $star2 + $star1);
            $avg= ($star5 *5+ $star4 *4+ $star3 *3+ $star2 *2+ $star1)/$sum_people;    
            $avg = round($avg,2);
            $restaurants[$i]['rate'][0] = ['restaurant_id'=>$restaurants[$i]->id,'avg_rate' => $avg,'sum_people' =>$sum_people];
        }
        $data = [
            "status" => 200,
            "method" => "getRestaurantByKeyword",
            "message" => "success",
            "data"=> $restaurants
        ];
        return response()->json($data, 200);
    }
    public function getRestaurantByTag(Request $request){
        $request->validate([
            'tag_id' => 'required|int',
        ]);
        $tag= RestaurantTag::where('id','=',$request->tag_id)->first(); //單一的Tag模型
        $restaurants = $tag->restaurants()->get();
        for($i = 0;$i<count($restaurants);$i++){
            $restaurants[$i]['tags'] = $restaurants[$i]->tags()->get();
            $restaurants[$i]['rate'] = $restaurants[$i]->Rates()->get();
            $star5 = $restaurants[$i]['rate'][0]->star5;
            $star4 = $restaurants[$i]['rate'][0]->star4;
            $star3 = $restaurants[$i]['rate'][0]->star3;
            $star2 = $restaurants[$i]['rate'][0]->star2;
            $star1 = $restaurants[$i]['rate'][0]->star1;
            $sum_people = ($star5 + $star4 + $star3 + $star2 + $star1);
            $avg= ($star5 *5+ $star4 *4+ $star3 *3+ $star2 *2+ $star1)/$sum_people;    
            $avg = round($avg,2);
            $restaurants[$i]['rate'][0] = ['restaurant_id'=>$restaurants[$i]->id,'avg_rate' => $avg,'sum_people' =>$sum_people];
        }
        $data = [
            "status" => 200,
            "method" => "getRestaurantByTag",
            "message" => "success",
            "data" => $restaurants
        ];
        return response()->json($data, 200);
    }
    public function getAllDish(Request $request)
    {
        $customer = $request->user();
        $request->validate([
            'ID' => 'required|int',
        ]);
        $Dish = Dish::where('restaurant_id','=', $request->ID)->get();
        $data = [
            "status" => 200,
            "method" => "getAllDish",
            "message" => "success",
            "data" => $Dish
        ];
        return response()->json($data, 200);
    }
}
