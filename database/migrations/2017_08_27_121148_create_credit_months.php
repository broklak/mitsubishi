<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditMonths extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_months', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('months');
            $table->tinyInteger('status')->comment('0:not active, 1:active')->default(1);
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('dealers', function (Blueprint $table) {
            $table->integer('area')->after('address')->nullable();
        });

        Schema::create('insurance_rate_head', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('car_category_id');
            $table->integer('car_model_id');
            $table->integer('leasing_id');
            $table->string('area', 255);
            $table->tinyInteger('status')->comment('0:not active, 1:active')->default(1);
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('insurance_rate_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('insurance_rate_id');
            $table->tinyInteger('type')->default(1)->comment('1:All Risk, 2:TLO');
            $table->integer('years');
            $table->float('rate');
            $table->integer('created_by');
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
        Schema::dropIfExists('credit_months');
        Schema::dropIfExists('insurance_rate_head');
        Schema::dropIfExists('insurance_rate_detail');
        Schema::table('dealers', function (Blueprint $table) {
            $table->dropColumn('area');
        });

        
    }
}
