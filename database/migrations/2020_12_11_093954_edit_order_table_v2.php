<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditOrderTableV2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('Orders', function (Blueprint $table) {
            $table->integer('delivery_fee');
            $table->integer('food_price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Orders', function (Blueprint $table) {
            $table->dropColumn('delivery_fee');
            $table->dropColumn('food_price');
        });
    }
}
