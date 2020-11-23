<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuraeatTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Customers', function (Blueprint $table) {
            $table->id();
            $table->string('phone')->unique();
            $table->string('email')->unique();
            $table->string('name');
            $table->string('address');
            $table->string('password');
            $table->timestamps();
        });
        Schema::create('DeliveryMans', function (Blueprint $table) {
            $table->id();
            $table->string('phone')->unique();
            $table->string('email')->unique();
            $table->string('name');
            $table->string('license_id');
            $table->string('password');
            $table->timestamps();
        });
        Schema::create('DeliveryManRate', function (Blueprint $table) {
            $table->bigInteger('delivery_man_id')->unsigned()->index();
            $table->foreign('delivery_man_id')->references('id')->on('DeliveryMans')->onDelete('cascade');
            $table->integer('star5');
            $table->integer('star4');
            $table->integer('star3');
            $table->integer('star2');
            $table->integer('star1');
        });
        Schema::create('Restaurants', function (Blueprint $table) {
            $table->id();
            $table->string('phone')->unique();
            $table->string('name');
            $table->string('address');
            $table->text('img');
            $table->string('email')->unique();
            $table->string('password');
            $table->timestamps();
        });
        Schema::create('RestaurantRates', function (Blueprint $table) {
            $table->bigInteger('restaurant_id')->unsigned()->index();
            $table->foreign('restaurant_id')->references('id')->on('Restaurants')->onDelete('cascade');
            $table->integer('star5');
            $table->integer('star4');
            $table->integer('star3');
            $table->integer('star2');
            $table->integer('star1');
        });
        Schema::create('RestaurantTags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
        Schema::create('RestaurantTags_xref', function (Blueprint $table) {
            $table->bigInteger('restaurant_tag')->unsigned()->index();
            $table->bigInteger('restaurant_id')->unsigned()->index();
            $table->foreign('restaurant_tag')->references('id')->on('RestaurantTags')->onDelete('cascade');
            $table->foreign('restaurant_id')->references('id')->on('Restaurants')->onDelete('cascade');
        });
        Schema::create('Dishes', function (Blueprint $table) {
           $table->id();
            $table->bigInteger('restaurant_id')->unsigned()->index();
            $table->foreign('restaurant_id')->references('id')->on('Restaurants')->onDelete('cascade');
            $table->string('name');
            $table->integer('price');
            $table->text('img');
            $table->timestamps();
            $table->integer('making_time');
        });
        Schema::create('Cart', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('dish_id')->unsigned()->index();
            $table->bigInteger('customer_id')->unsigned()->index();
            $table->foreign('dish_id')->references('id')->on('Dishes')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('Customers')->onDelete('cascade');
            $table->integer('status');
            $table->integer('order_id');
            $table->timestamps();
        });
        Schema::create('Orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('delivery_man_id')->unsigned()->index();
            $table->bigInteger('customer_id')->unsigned()->index();
            $table->foreign('delivery_man_id')->references('id')->on('DeliveryMans')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('Customers')->onDelete('cascade');
            $table->integer('status');
            $table->integer('type');
            $table->integer('distance');
            $table->dateTime('send_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Customers');
        Schema::dropIfExists('DeliveryMans');
        Schema::dropIfExists('DeliveryManRate');
        Schema::dropIfExists('Restaurants');
        Schema::dropIfExists('RestaurantRates');
        Schema::dropIfExists('RestaurantTags');
        Schema::dropIfExists('RestaurantTags_xref');
        Schema::dropIfExists('Dishes');
        Schema::dropIfExists('Cart');
        Schema::dropIfExists('Orders');

    }
}
