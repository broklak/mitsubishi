<?php

use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('permissions')->truncate();
        $json = File::get("database/data/permission.json");
        $data = json_decode($json);
        foreach ($data as $obj) {
            DB::table('permissions')->insert([
                'name'   		=> $obj->name,
                'display_name'  => $obj->display_name
            ]);
        }
    }
}
