<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use Illuminate\Http\Request;

class RestaurantMenuController extends Controller
{
    public function getAllDish(Request $request){
        $restaurant = $request->user();
        $Dish = Dish::paginate(20);
        $data = [
            "status" => 200,
            "method" => "getAllRestaurant",
            "message" => "success",
            "data"=> $restaurants
        ];
        return response()->json($data, 200);
    }

    public function getRestaurantByID(Request $request)
    {
        $request->validate([
            'ID' => 'required|string',
        ]);
        $restaurants = Restaurant::where('id', '=', $request->ID)->get();
        for ($i = 0; $i < count($restaurants); $i++) {
            $restaurants[$i]['tags'] = $restaurants[$i]->tags()->get();
        }
        $data = [
            "status" => 200,
            "method" => "getRestaurantByID",
            "message" => "success",
            "data" => $restaurants
        ];
    }
    public function addDish(Request $request)
    {
        $restaurant = $request->user();
        $request->validate([
            'making_time' => 'required|int',
            'name'=>'required|string',
            'img'=>'required|string',
            'price'=>'required|int'
        ]);
        $dish=new Dish;
        $dish->name=$request->name;
        $dish->making_time=$request->making_time;
        $dish->img=$request->img;
        $dish->price=$request->price;
        $dish->restaurant_id=$restaurant->id;
        $dish->save();
    }
    public function editDish(Request $request)
    {

    }
    public function deleteDish(Request $request)
    {

    }
}

