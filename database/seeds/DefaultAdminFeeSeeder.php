<?php

use Illuminate\Database\Seeder;

class DefaultAdminFeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('default_admin_fee')->truncate();
        DB::table('default_admin_fee')->insert([
            'cost'      		=> 2000000,
            'created_by'  		=> 0,
        ]);
    }
}
