<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSimulation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('simulation', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('leasing_id');
            $table->integer('car_category_id');
            $table->integer('car_model_id');
            $table->integer('car_type_id');
            $table->integer('car_year');
            $table->integer('price');
            $table->integer('dp_amount');
            $table->integer('duration');
            $table->integer('dp_percentage');
            $table->integer('admin_cost');
            $table->integer('installment_cost');
            $table->integer('interest_rate');
            $table->integer('insurance_cost');
            $table->integer('total_dp');
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->timestamps();
        });

        Schema::create('delivery_order', function (Blueprint $table) {
            $table->increments('id');
            $table->string('do_code', 100);
            $table->date('do_date');
            $table->integer('spk_id');
            $table->string('spk_doc_code', 100);
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
        Schema::dropIfExists('simulation');
        Schema::dropIfExists('delivery_order');
    }
}
