<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use Illuminate\Http\Request;

class RestaurantMenuController extends Controller
{
    public function getAllDish(Request $request){
        $restaurant = $request->user();
        $Dish = Dish::where('restaurant_id', '=', $restaurant->id)->get();
        $data = [
            "status" => 200,
            "method" => "getAllDish",
            "message" => "success",
            "data"=> $Dish
        ];
        return response()->json($data, 200);
    }

    public function getDishbyID(Request $request)
    {
        $request->validate([
            'ID' => 'required|string',
        ]);
        $restaurant = $request->user();
        $dish = Dish::where('id', '=', $request->ID)->get();
        $data = [
            "status" => 200,
            "method" => "getDishbyID",
            "message" => "success",
            "data" => $dish
        ];
        return response()->json($data, 200);
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
        $data = [
            "status" => 200,
            "method" => "addDish",
            "message" => "success"
        ];
        return response()->json($data, 200);
    }
    public function editDish(Request $request)
    {
        $restaurant = $request->user();
        $request->validate([
            'ID' => 'required|string',
            'making_time' => 'required|int',
            'name'=>'required|string',
            'img'=>'required|string',
            'price'=>'required|int'
        ]);
        $dish = Dish::findOrFail($request->ID);
        $dish->name=$request->name;
        $dish->making_time=$request->making_time;
        $dish->img=$request->img;
        $dish->price=$request->price;
        $dish->restaurant_id=$restaurant->id;
        $dish->save();
        $data = [
            "status" => 200,
            "method" => "editDish",
            "message" => "success"
        ];
        return response()->json($data, 200);
    }
    public function deleteDish(Request $request)
    {
        $restaurant = $request->user();
        $request->validate([
            'ID' => 'required|string',
        ]);
        $dish = Dish::findOrFail($request->ID);
        $dish->delete();
        $data = [
            "status" => 200,
            "method" => "deleteDish",
            "message" => "success"
        ];
        return response()->json($data, 200);
    }
}

