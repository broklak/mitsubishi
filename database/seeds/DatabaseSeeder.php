<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(RoleUserSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(PermissionRoleSeeder::class);
        $this->call(AreaSeeder::class);
        $this->call(CreditMonthSeeder::class);
        $this->call(DefaultAdminFeeSeeder::class);
        $this->call(FleetRateSeeder::class);
        $this->call(ServerSecretSeeder::class);
    }
}
