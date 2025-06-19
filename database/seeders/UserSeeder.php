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
        // Hapus semua data user lama
        \DB::table('users')->truncate();

        $userData = [
            [
                'name' => 'Admin Pertagas',
                'email' => 'Admin@pertagas.com',
                'password' => bcrypt('123Pertagas'),
            ],
            [
                'name' => 'userpertamina1',
                'email' => 'user@gmail.com',
                'password' => bcrypt('123'),
            ],
            [
                'name' => 'usertes',
                'email' => 'tes@gmail.com',
                'password' => bcrypt('123'),
            ],
            [
                'name' => 'monitor',
                'email' => 'monitor@gmail.com',
                'password' => bcrypt('123'),
            ]
        ];
    
        foreach ($userData as $user) {
            User::create($user);
        }
    }
    
}