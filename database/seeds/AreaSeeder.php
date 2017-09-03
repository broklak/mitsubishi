<?php

use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('areas')->truncate();
        DB::table('areas')->insert([
            'name'      		=> 'Jakarta',
            'created_by'  		=> 0,
        ]);

        DB::table('areas')->insert([
            'name'      		=> 'Karawang',
            'created_by'  		=> 0,
        ]);

        DB::table('areas')->insert([
            'name'      		=> 'Cilegon',
            'created_by'  		=> 0,
        ]);
    }
}
