<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@cafetrack.com',
            'password' => Hash::make('6579'),
            'department' => 'N/A',
            'intake' => 'N/A',
            'student_id' => 'N/A',
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'ACID',
            'email' => 'smnafizulislam@gmail.com',
            'password' => Hash::make('Acid2001@'),
            'department' => 'CSE',
            'intake' => '51',
            'student_id' => '2225103386',
            'role' => 'user',
        ]);
    }
}
