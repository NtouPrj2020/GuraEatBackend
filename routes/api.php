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
    });

    Route::group(['prefix' => 'users', 'middleware' => ['auth:sanctum']], function () {
        Route::group(['prefix' => 'customer'], function () {
            Route::post('/logout', 'App\Http\Controllers\AuthController@customerLogout');
            Route::get('/restaurant/all', 'App\Http\Controllers\Restaurantcontroller@restaurants');
        });
        Route::group(['prefix' => 'delivery_man'], function () {
            Route::post('/logout', 'App\Http\Controllers\AuthController@deliveryManLogout');
        });
        Route::group(['prefix' => 'restaurant'], function () {
        });
    });
});
