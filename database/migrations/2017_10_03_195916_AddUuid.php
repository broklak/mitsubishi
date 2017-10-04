<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUuid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('simulation', function (Blueprint $table) {
            $table->string("uuid", 255)->nullable();
        });

        Schema::table('order_head', function (Blueprint $table) {
            $table->string("uuid", 255)->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('simulation', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });

        Schema::table('order_head', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
}
