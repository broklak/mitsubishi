<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
         	'first_name' => 'Admin',
         	'last_name' => 'Admin',
         	'username' => 'admin',
         	'password' => bcrypt('huhuhut'),
         	'job_position_id' => 0,
         	'created_by' => 0
        ]);
    }
}
