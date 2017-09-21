<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ServerSecret extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('server_secret', function (Blueprint $table) {
            $table->increments('id');
            $table->string('secret', 255);
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->date('valid_login')->nullable()->after('start_work');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('server_secret');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('valid_login');
        });
    }
}
