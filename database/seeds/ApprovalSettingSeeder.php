<?php

use Illuminate\Database\Seeder;

class ApprovalSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('approval_setting')->truncate();
        DB::table('approval_setting')->insert([
            'level'      		=> 1,
            'job_position_id'	=> 2,
            'updated_by'  		=> 0,
        ]);

        DB::table('approval_setting')->insert([
            'level'      		=> 2,
            'job_position_id'	=> 3,
            'updated_by'  		=> 0,
        ]);
    }
}
