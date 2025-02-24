<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userData = [
            [
                'name' => 'userpertamina1',
                'email' => 'user@gmail.com',
                'password' => bcrypt('123'),
            ],

            [
                'name' => 'usertes',
                'email' => 'tes@gmail.com',
                'password' => bcrypt('123'),
            ]
        ];
    
        foreach ($userData as $user) {
            User::create($user);
        }
    }
    
}