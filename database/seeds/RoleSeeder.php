<?php

use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('roles')->truncate();
        DB::table('roles')->insert([
            'name'          => 'sales',
            'display_name'  => 'Sales',
        ]);
        DB::table('roles')->insert([
            'name'          => 'supervisor',
            'display_name'  => 'Supervisor',
        ]);
        DB::table('roles')->insert([
            'name'          => 'manager',
            'display_name'  => 'Manager',
        ]);
    }
}
