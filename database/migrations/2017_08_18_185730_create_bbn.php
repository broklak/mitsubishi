<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBbn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bbn', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 75);
            $table->tinyInteger('status')->comment('0:not active, 1:active')->default(1);
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('leasing', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 75);
            $table->string('contact_name', 75)->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('fax', 50)->nullable();
            $table->string('email', 50)->nullable();
            $table->text('address')->nullable();
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
        Schema::dropIfExists('bbn');
        Schema::dropIfExists('leasing');
    }
}
