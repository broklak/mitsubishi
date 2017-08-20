<?php

use Illuminate\Database\Seeder;

class JobPositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('job_position')->truncate();
        DB::table('job_position')->insert([
            'name'  => 'Admin',
            'created_by'  => 1,
        ]);
        DB::table('job_position')->insert([
            'name'  => 'Supervisor',
            'created_by'  => 1,
        ]);
        DB::table('job_position')->insert([
            'name'  => 'Manager',
            'created_by'  => 1,
        ]);
    }
}
