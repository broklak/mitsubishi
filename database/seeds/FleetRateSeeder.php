<?php

use Illuminate\Database\Seeder;

class FleetRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('fleet_rate')->truncate();
        DB::table('fleet_rate')->insert([
            'rate'      		=> 50
        ]);
    }
}
