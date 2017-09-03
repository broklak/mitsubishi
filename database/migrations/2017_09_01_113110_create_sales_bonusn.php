<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesBonusn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_bonus_head', function (Blueprint $table) {
            $table->increments('id');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('sales_bonus_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sales_bonus_id');
            $table->integer('min_car');
            $table->integer('max_car');
            $table->integer('amount');
            $table->timestamps();
        });

        Schema::create('fleet_rate', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('rate');
            $table->integer('updated_by')->nullable();
            $table->timestamps();
        });

        Schema::table('car_types', function (Blueprint $table) {
            $table->integer('insentif_amount')->after('model_id')->nullable();
        });

        Schema::table('delivery_order', function (Blueprint $table) {
            $table->tinyInteger('is_fleet')->after('spk_doc_code')->nullable()->comment('1:yes, 2:no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_bonus_head');
        Schema::dropIfExists('sales_bonus_detail');
        Schema::dropIfExists('fleet_rate');

        Schema::table('car_types', function (Blueprint $table) {
            $table->dropColumn('insentif_amount');
        });

        Schema::table('delivery_order', function (Blueprint $table) {
            $table->dropColumn('is_fleet');
        });
    }
}
