<?php

use Illuminate\Database\Seeder;

class PermissionRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	// DB::table('permission_role')->truncate();
        $data = DB::table('permissions')->get();
        foreach ($data as $key => $value) {
        	DB::table('permission_role')->insert([
                'permission_id'   		=> $value->id,
                'role_id'  				=> 1
            ]);
        }
    }
}
