<?php

use Illuminate\Database\Seeder;

class ServerSecretSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('server_secret')->truncate();
        DB::table('server_secret')->insert([
            'secret'      		=> 'mitsu-server-secret'
        ]);
    }
}
