<?php

use Illuminate\Database\Seeder;

class CreditMonthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('credit_months')->truncate();
        DB::table('credit_months')->insert([
            'months'      		=> 12,
            'created_by'  		=> 0,
        ]);

        DB::table('credit_months')->insert([
            'months'      		=> 24,
            'created_by'  		=> 0,
        ]);

        DB::table('credit_months')->insert([
            'months'      		=> 36,
            'created_by'  		=> 0,
        ]);

        DB::table('credit_months')->insert([
            'months'      		=> 48,
            'created_by'  		=> 0,
        ]);
    }
}
