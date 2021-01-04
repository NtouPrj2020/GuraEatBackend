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
        Route::post('/customer/signup', 'App\Http\Controllers\AuthController@customerSignUp')->name('customer.signup');
        Route::post('/customer/login', 'App\Http\Controllers\AuthController@customerLogin')->name('customer.login');
        Route::post('/customer/createPwdResetRequest', 'App\Http\Controllers\AuthController@customerCreatePasswordResetRequest')->name('customer.createPwdResetRequest');

        Route::post('/delivery_man/signup', 'App\Http\Controllers\AuthController@deliveryManSignUp')->name('delivery_man.signup');
        Route::post('/delivery_man/login', 'App\Http\Controllers\AuthController@deliveryManLogin')->name('delivery_man.login');

        Route::post('/restaurant/signup', 'App\Http\Controllers\AuthController@restaurantSignUp')->name('restaurant.signup');
        Route::post('/restaurant/login', 'App\Http\Controllers\AuthController@restaurantLogin')->name('restaurant.login');
    });

    Route::group(['prefix' => 'users', 'middleware' => ['auth:sanctum']], function () {
        Route::group(['prefix' => 'customer'], function () {
            Route::post('/logout', 'App\Http\Controllers\AuthController@customerLogout');
            Route::get('/info', 'App\Http\Controllers\CustomerInfoController@getCustomer');
            Route::put('/info', 'App\Http\Controllers\CustomerInfoController@editCustomer');
            Route::get('/restaurant/all', 'App\Http\Controllers\CustomerGetRestaurantInfoController@getAllRestaurant');
            Route::get('/restaurant/searchByID', 'App\Http\Controllers\CustomerGetRestaurantInfoController@getRestaurantByID');
            Route::get('/restaurant/searchByKeyword', 'App\Http\Controllers\CustomerGetRestaurantInfoController@getRestaurantByKeyword')->name('customer.searchByKeyword');
            Route::get('/restaurant/searchByTag', 'App\Http\Controllers\CustomerGetRestaurantInfoController@getRestaurantByTag');
            Route::get('/restaurant/getAllDish', 'App\Http\Controllers\CustomerGetRestaurantInfoController@getAllDish');
            Route::get('/getDistanceAndTimeByAddress', 'App\Http\Controllers\CheckoutController@getDistanceAndTimeByAddress');
            Route::get('/addressToLocation', 'App\Http\Controllers\CheckoutController@addressToLocation');
            Route::get('/locationToAddress', 'App\Http\Controllers\CheckoutController@locationToAddress');
            Route::post('/changeRoleCustomer', 'App\Http\Controllers\AuthController@changeRoleCustomer');
            Route::get('/order/getAllOrders', 'App\Http\Controllers\OrderController@getAllOrders');
            Route::get('/order/getOrderByID', 'App\Http\Controllers\OrderController@getOrderByID');
            Route::post('/order/send', 'App\Http\Controllers\CheckoutController@checkoutAuto');
            Route::post('/order/rate', 'App\Http\Controllers\confirmOrderController@rateOrder');
            Route::get('/order/now', 'App\Http\Controllers\OrderController@customergetOrdernow');
            Route::get('/getDeliveryManLocation', 'App\Http\Controllers\GeographyController@getLocationByDeliveryManID');
        });
        Route::group(['prefix' => 'delivery_man'], function () {
            Route::get('/info', 'App\Http\Controllers\DeliveryManInfoController@getDeliveryMan');
            Route::put('/info', 'App\Http\Controllers\DeliveryManInfoController@editDeliveryMan');
            Route::put('/status', 'App\Http\Controllers\DeliveryManChangeStateController@changeState');
            Route::get('/addressToLocation', 'App\Http\Controllers\CheckoutController@addressToLocation');
            Route::get('/locationToAddress', 'App\Http\Controllers\CheckoutController@locationToAddress');
            Route::get('/location', 'App\Http\Controllers\GeographyController@getDeliveryManLocation');
            Route::post('/location', 'App\Http\Controllers\GeographyController@editDeliveryManLocation');
            Route::get('/getDistanceAndTimeByAddress', 'App\Http\Controllers\CheckoutController@getDistanceAndTimeByAddress');
            Route::post('/logout', 'App\Http\Controllers\AuthController@deliveryManLogout');
            Route::get('/order/getAllOrders', 'App\Http\Controllers\DeliveryController@getAllOrders');
            Route::get('/order/getOrderByID', 'App\Http\Controllers\DeliveryController@getOrderByID');
            Route::post('/changeRoleDeliveryMan', 'App\Http\Controllers\AuthController@changeRoleDeliveryMan');
            Route::get('/order/now', 'App\Http\Controllers\DeliveryController@deliverymangetOrdernow');
            Route::put('/order/changestatus', 'App\Http\Controllers\DeliveryController@deliveryManChangeOrderStatus');
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
