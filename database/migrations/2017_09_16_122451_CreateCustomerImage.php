<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerImage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_image', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_id');
            $table->integer('type')->comment('1:ktp, 2:sim, 3:passport');
            $table->string('id_number', 100);
            $table->string('filename', 255);
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
        Schema::dropIfExists('customer_image');
    }
}
