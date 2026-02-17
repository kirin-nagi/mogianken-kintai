<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AdminUserSeeder::class);
        $this->call(StaffUserSeeder::class);
        $this->call(AttendanceSeeder::class);
        $this->call(ApprovalSeeder::class);
        $this->call(RequestSeeder::class);
    }
}
