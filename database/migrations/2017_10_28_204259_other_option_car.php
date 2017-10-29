<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OtherOptionCar extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_head', function (Blueprint $table) {
            $table->string('customer_name', 255)->after('customer_image_id')->nullable();
            $table->string('type_others', 255)->after('type_id')->nullable();
        });

        Schema::create('car_colors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->softDeletes();
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
        Schema::table('order_head', function (Blueprint $table) {
            $table->dropColumn('customer_name');
            $table->dropColumn('type_others');
        });

        Schema::dropIfExists('car_colors');
    }
}
