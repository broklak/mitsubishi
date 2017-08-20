<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApprovalSetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('approval_setting', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('level');
            $table->integer('job_position_id');
            $table->integer('updated_by');
            $table->timestamps();
        });

        Schema::create('order_approval', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id');
            $table->integer('level_approved');
            $table->integer('approved_by');
            $table->timestamps();
        });

        Schema::create('order_log', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id');
            $table->string('desc', 100);
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
        Schema::dropIfExists('approval_setting');
        Schema::dropIfExists('order_approval');
        Schema::dropIfExists('order_log');
    }
}
