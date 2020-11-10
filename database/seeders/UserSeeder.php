<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Johnny',
            'email' => 'asd',
            'password' => Hash::make('123456')
        ]);

        DB::table('users')->insert([
            'name' => 'Joe',
            'email' => 'qwe',
            'password' => Hash::make('123456')
        ]);

        DB::table('users')->insert([
            'name' => 'Joebert',
            'email' => 'zxc',
            'password' => Hash::make('123456')
        ]);

        DB::table('users')->insert([
            'name' => 'Jobe',
            'email' => 'qwer',
            'password' => Hash::make('123456')
        ]);
    }
}
