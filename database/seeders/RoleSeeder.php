<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('roles')->insert([
            ['role_id' => 1, 'name' => 'user'],
            ['role_id' => 2, 'name' => 'staff'],
            ['role_id' => 3, 'name' => 'manager'],
            ['role_id' => 4, 'name' => 'admin'],
        ]);
    }
}
