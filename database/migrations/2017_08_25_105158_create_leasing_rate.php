<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeasingRate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leasing_rate_head', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('leasing_id');
            $table->integer('car_model_id');
            $table->integer('car_type_id');
            $table->string('areas', 255);
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('karoseri')->nullable();
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('leasing_rate_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('leasing_rate_id');
            $table->integer('dp_min');
            $table->integer('dp_max');
            $table->integer('months');
            $table->float('rate');
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->timestamps();
        });

        Schema::create('areas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 75);
            $table->tinyInteger('status')->comment('0:not active, 1:active')->default(1);
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('default_admin_fee', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cost');
            $table->tinyInteger('status')->comment('0:not active, 1:active')->default(1);
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('leasing', function (Blueprint $table) {
            $table->integer('admin_cost')->after('address');
            $table->string('areas', 255)->nullable()->after('address');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leasing_rate');
        Schema::dropIfExists('leasing_rate_head');
        Schema::dropIfExists('leasing_rate_detail');
        Schema::dropIfExists('areas');
        Schema::dropIfExists('default_admin_fee');

        Schema::table('leasing', function (Blueprint $table) {
            $table->dropColumn('admin_cost');
            $table->dropColumn('areas');
        });
    }
}
