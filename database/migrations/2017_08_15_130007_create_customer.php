<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name', 75);
            $table->string('last_name', 75)->nullable();
            $table->integer('id_type')->comment('1:ktp, 2:sim')->default(1);
            $table->string('id_number', 75);
            $table->string('phone', 50);
            $table->string('email', 75)->nullable();
            $table->text('address')->nullable();
            $table->string('job', 75)->nullable();
            $table->string('npwp', 75)->nullable();
            $table->string('image', 100)->nullable();
            $table->tinyInteger('status')->comment('0:not active, 1:active')->default(1);
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
        Schema::dropIfExists('customers');
    }
}
