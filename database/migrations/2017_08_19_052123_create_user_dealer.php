<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserDealer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_dealer', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('dealer_id');
            $table->timestamps();
        });

        Schema::create('job_position', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 75);
            $table->tinyInteger('status')->comment('0:not active, 1:active')->default(1);
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->softDeletes()->after('password');
            $table->tinyInteger('status')->comment('0:not active, 1:active')->default(1)->after('password');
            $table->integer('created_by')->after('password');
            $table->integer('updated_by')->nullable()->after('password');
            $table->integer('job_position_id')->after('password');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_dealer');

        Schema::dropIfExists('job_position');

        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn('status');
            $table->dropColumn('created_by');
            $table->dropColumn('updated_by');
            $table->dropColumn('job_position_id');
        });
    }
}
