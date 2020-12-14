<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use App\Models\RestaurantTag;
use Illuminate\Http\Request;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Hash;

class CustomerGetRestaurantInfoController extends Controller
{
    public static function sum_people($restaurant){
        $rate = $restaurant->rates()->first();
        $star5 = $rate->star5;
        $star4 = $rate->star4;
        $star3 = $rate->star3;
        $star2 = $rate->star2;
        $star1 = $rate->star1;
        return ($star5 + $star4 + $star3 + $star2 + $star1);
    }

    public static function count_avg($restaurant){
        $rate = $restaurant->rates()->first();
        $star5 = $rate->star5;
        $star4 = $rate->star4;
        $star3 = $rate->star3;
        $star2 = $rate->star2;
        $star1 = $rate->star1;
        if(CustomerGetRestaurantInfoController::sum_people($restaurant)>0)
        {
            $total=($star5 *5+ $star4 *4+ $star3 *3+ $star2 *2+ $star1)/CustomerGetRestaurantInfoController::sum_people($restaurant);
            $ans = round($total,2);
        }
        else
            $ans=0;
        return  $ans;
    }

    public function getAllRestaurant(Request $request){
        $restaurants = Restaurant::paginate(20);
        for($i = 0;$i<count($restaurants);$i++){
            $restaurants[$i]['tags'] = $restaurants[$i]->tags()->get();
            $rate = $restaurants[$i]->rates()->first();
            if ($rate!=null) {
                $restaurants[$i]['avg_rate'] = $this->count_avg($restaurants[$i]);
                $restaurants[$i]['sum_people'] =$this->sum_people($restaurants[$i]);
            }
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
        $rate = $restaurants->rates()->first();
            if ($rate!=null) {
                $restaurants['avg_rate'] = $this->count_avg($restaurants);
                $restaurants['sum_people'] =$this->sum_people($restaurants);
            }

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
            $rate = $restaurants[$i]->rates()->first();
            if ($rate!=null) {
                $restaurants[$i]['avg_rate'] = $this->count_avg($restaurants[$i]);
                $restaurants[$i]['sum_people'] =$this->sum_people($restaurants[$i]);
            }
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
            $rate = $restaurants[$i]->rates()->first();
            if ($rate!=null) {
                $restaurants[$i]['avg_rate'] = $this->count_avg($restaurants[$i]);
                $restaurants[$i]['sum_people'] =$this->sum_people($restaurants[$i]);
            }
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
