<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1'], function () {

    Route::group(['prefix' => 'guest'], function () {
        Route::post('/customer/signup', 'App\Http\Controllers\AuthController@customerSignUp');
        Route::post('/customer/login', 'App\Http\Controllers\AuthController@customerLogin');
        Route::post('/customer/createPwdResetRequest', 'App\Http\Controllers\AuthController@customerCreatePasswordResetRequest');

        Route::post('/delivery_man/signup', 'App\Http\Controllers\AuthController@deliveryManSignUp');
        Route::post('/delivery_man/login', 'App\Http\Controllers\AuthController@deliveryManLogin');

        Route::post('/restaurant/signup', 'App\Http\Controllers\AuthController@restaurantSignUp');
        Route::post('/restaurant/login', 'App\Http\Controllers\AuthController@restaurantLogin');
    });

    Route::group(['prefix' => 'users', 'middleware' => ['auth:sanctum']], function () {
        Route::group(['prefix' => 'customer'], function () {
            Route::post('/logout', 'App\Http\Controllers\AuthController@customerLogout');
            Route::get('/info', 'App\Http\Controllers\CustomerInfoController@getCustomer');
            Route::get('/restaurant/all', 'App\Http\Controllers\CustomerGetRestaurantInfoController@getAllRestaurant');
            Route::get('/restaurant/searchByID', 'App\Http\Controllers\CustomerGetRestaurantInfoController@getRestaurantByID');
            Route::get('/restaurant/searchByKeyword', 'App\Http\Controllers\CustomerGetRestaurantInfoController@getRestaurantByKeyword');
            Route::get('/restaurant/searchByTag', 'App\Http\Controllers\CustomerGetRestaurantInfoController@getRestaurantByTag');
        });
        Route::group(['prefix' => 'delivery_man'], function () {
            Route::post('/logout', 'App\Http\Controllers\AuthController@deliveryManLogout');
        });
        Route::group(['prefix' => 'restaurant'], function () {
            Route::get('/info', 'App\Http\Controllers\RestaurantInfoController@getInfo');
            Route::post('/menu/addDish', 'App\Http\Controllers\RestaurantMenuController@addDish');
            Route::delete('/menu/deleteDish', 'App\Http\Controllers\RestaurantMenuController@deleteDish');
            Route::put('/menu/editDish', 'App\Http\Controllers\RestaurantMenuController@editDish');
            Route::get('/menu/getAllDish', 'App\Http\Controllers\RestaurantMenuController@getAllDish');
            Route::get('/menu/getDishbyID', 'App\Http\Controllers\RestaurantMenuController@getDishbyID');
        });
    });
});
