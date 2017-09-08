<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStartWork extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->date('start_work')->nullable()->after('password');
            $table->integer('supervisor_id')->nullable()->after('password');
            $table->datetime('deleted_at')->nullable()->change();
        });

        Schema::table('simulation', function (Blueprint $table) {
            $table->string('customer_name', 255)->nullable()->after('leasing_id');
            $table->float('interest_rate')->nullable()->change();
        });

        Schema::table('order_head', function (Blueprint $table) {
            $table->integer('status')->default(1)->comment('1:Approve Pending, 2:Not Approved, 3:Approved, 4:Order Pending, 5:Order Confirmed, 6:Partial DO, 7:DO')->change();
        });

        Schema::table('order_approval', function (Blueprint $table) {
            $table->text('reject_reason')->nullable()->after('role_name');
            $table->tinyInteger('type')->default(1)->comment('1:approved, 2:not approved')->after('role_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('start_work');
            $table->dropColumn('supervisor_id');
        });

        Schema::table('simulation', function (Blueprint $table) {
            $table->dropColumn('customer_name');
        });

        Schema::table('order_approval', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('reject_reason');
        });
    }
}
