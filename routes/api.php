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
            Route::put('/info', 'App\Http\Controllers\CustomerInfoController@editCustomer');
            Route::get('/restaurant/all', 'App\Http\Controllers\CustomerGetRestaurantInfoController@getAllRestaurant');
            Route::get('/restaurant/searchByID', 'App\Http\Controllers\CustomerGetRestaurantInfoController@getRestaurantByID');
            Route::get('/restaurant/searchByKeyword', 'App\Http\Controllers\CustomerGetRestaurantInfoController@getRestaurantByKeyword');
            Route::get('/restaurant/searchByTag', 'App\Http\Controllers\CustomerGetRestaurantInfoController@getRestaurantByTag');
            Route::get('/restaurant/getAllDish', 'App\Http\Controllers\CustomerGetRestaurantInfoController@getAllDish');
            Route::get('/getDistanceAndTimeByAddress', 'App\Http\Controllers\CheckoutController@getDistanceAndTimeByAddress');
            Route::post('/changeRoleCustomer', 'App\Http\Controllers\AuthController@changeRoleCustomer');
        });
        Route::group(['prefix' => 'delivery_man'], function () {
            Route::get('/info', 'App\Http\Controllers\DeliveryManInfoController@getDeliveryMan');
            Route::put('/info', 'App\Http\Controllers\DeliveryManInfoController@editDeliveryMan');
            Route::put('/status', 'App\Http\Controllers\DeliveryManChangeStateController@changeState');
            Route::get('/location', 'App\Http\Controllers\GeographyController@getDeliveryManLocation');
            Route::post('/location', 'App\Http\Controllers\GeographyController@editDeliveryManLocation');
            Route::post('/logout', 'App\Http\Controllers\AuthController@deliveryManLogout');
            Route::post('/changeRoleDeliveryMan', 'App\Http\Controllers\AuthController@changeRoleDeliveryMan');
        });
        Route::group(['prefix' => 'restaurant'], function () {
            Route::get('/info', 'App\Http\Controllers\RestaurantInfoController@getRestaurant');
            Route::put('/info', 'App\Http\Controllers\RestaurantInfoController@editRestaurant');
            Route::get('/tags', 'App\Http\Controllers\RestaurantInfoController@getAllTags');
            Route::post('/menu/addDish', 'App\Http\Controllers\RestaurantMenuController@addDish');
            Route::delete('/menu/deleteDish', 'App\Http\Controllers\RestaurantMenuController@deleteDish');
            Route::put('/menu/editDish', 'App\Http\Controllers\RestaurantMenuController@editDish');
            Route::get('/menu/getAllDish', 'App\Http\Controllers\RestaurantMenuController@getAllDish');
            Route::get('/menu/getDishbyID', 'App\Http\Controllers\RestaurantMenuController@getDishbyID');
            Route::post('/logout', 'App\Http\Controllers\AuthController@restaurantLogout');
            Route::post('/changeRoleRestaurant', 'App\Http\Controllers\AuthController@changeRoleRestaurant');
        });
    });
});
