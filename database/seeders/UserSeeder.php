<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pass = Crypt::encrypt('123456');
        DB::table('users')->insert([
            'name' => 'safa',
            'role' => 'admin',
            'email' => 'safa@gmail.com',
            'password' => $pass,
        ]);
    }
}
