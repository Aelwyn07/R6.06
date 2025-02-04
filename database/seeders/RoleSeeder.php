<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('roles')->insert([
            [
                'id' => 1,
                'level' => 0,
                'name' => 'administrator'
            ],
            [
                'id' => 2,
                'level' => 1,
                'name' => 'extended_reader'
            ],
            [
                'id' => 3,
                'level' => 2,
                'name' => 'reader'
            ],
        ]);
    }
}
