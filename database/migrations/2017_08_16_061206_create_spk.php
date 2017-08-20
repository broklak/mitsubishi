<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_head', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('dealer_id');
            $table->integer('customer_id');
            $table->string('spk_code', 75);
            $table->string('spk_doc_code', 75);
            $table->date('date');
            $table->string('npwp_image')->nullable();
            $table->string('stnk_name')->nullable();
            $table->string('stnk_address')->nullable();
            $table->string('faktur_conf')->nullable();
            $table->integer('model_id');
            $table->integer('type_id');
            $table->string('color')->nullable();
            $table->string('car_year')->nullable();
            $table->integer('qty');
            $table->integer('plat')->comment('1:hitam, 2:merah, 3:kuning');
            $table->integer('bbn_type')->nullable();
            $table->string('karoseri', 100)->nullable();
            $table->string('karoseri_type', 100)->nullable();
            $table->string('karoseri_spec', 100)->nullable();
            $table->integer('karoseri_price')->nullable();
            $table->tinyInteger('status')->comment('0:not active, 1:active')->default(1);
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('order_price', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id');
            $table->integer('price_off')->comment('harga off the road');
            $table->integer('price_on')->comment('harga on the road');
            $table->integer('cost_surat');
            $table->integer('discount');
            $table->integer('total_sales_price');
            $table->integer('down_payment_amount')->nullable();
            $table->integer('down_payment_percentage')->nullable();
            $table->date('down_payment_date')->nullable();
            $table->integer('jaminan_cost_amount')->nullable();
            $table->integer('jaminan_cost_percentage')->nullable();
            $table->integer('total_unpaid')->nullable();
            $table->tinyInteger('payment_method')->comment('1:cash, 2:leasing / credit');
            $table->tinyInteger('status')->comment('0:not active, 1:active')->default(1);
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('order_credit', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id');
            $table->integer('leasing_id')->nullable();
            $table->integer('year_duration')->nullable();
            $table->string('owner_name', 75)->nullable();
            $table->float('interest_rate')->nullable();
            $table->integer('admin_cost')->nullable();
            $table->integer('insurance_cost')->nullable();
            $table->integer('installment_cost')->nullable();
            $table->integer('other_cost')->nullable();
            $table->integer('total_down_payment')->nullable();
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
        Schema::dropIfExists('order_head');
        Schema::dropIfExists('order_price');
        Schema::dropIfExists('order_credit');
    }
}
