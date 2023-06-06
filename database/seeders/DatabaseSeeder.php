<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'Guest',
                'email' => 'guest@gmail.com',
                'password' => bcrypt('123')
            ],
            [
                'name' => 'Alyona',
                'email' => 'alyonaozhered@gmail.com',
                'password' => bcrypt('123'),
            ],
            [
                'name' => 'Varya',
                'email' => 'varvara@gmail.com',
                'password' => bcrypt('123'),
            ],
        ]);
    }
}
