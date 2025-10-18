<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'first_name' => 'Tushar',
                'last_name' => 'Kumar Roy',
                'email' => 'tusharkumarroy.dev@gmail.com',
                'password' => Hash::make('iloveyousonali'),
                'role' => 'master_admin',
                'bio' => 'System administrator and book enthusiast.',
            ],
            [
                'first_name' => 'Admin',
                'last_name' => 'User',
                'email' => 'admin@bookphile.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'bio' => 'System administrator and book enthusiast.',
            ],
            [
                'first_name' => 'Sonali',
                'last_name' => 'Rani',
                'email' => 'sobdolota@gmail.com',
                'password' => Hash::make('iloveyoutushar'),
                'role' => 'user',
                'bio' => 'I love reading fantasy and science fiction novels. Always looking for my next great read!',
            ],
            [
                'first_name' => 'Apu',
                'last_name' => 'Sharma',
                'email' => 'apusharma@gmail.com',
                'password' => Hash::make('apusharma123'),
                'role' => 'user',
                'bio' => 'I love reading fantasy and science fiction novels. Always looking for my next great read!',
            ],
            [
                'first_name' => 'Mosaddek',
                'last_name' => 'Ali',
                'email' => 'mosaddekali@gmail.com',
                'password' => Hash::make('mosaddekali123'),
                'role' => 'user',
                'bio' => 'I love reading fantasy and science fiction novels. Always looking for my next great read!',
            ],
            [
                'first_name' => 'Atifeen',
                'last_name' => 'Jubayer',
                'email' => 'atifeenjubayer@gmail.com',
                'password' => Hash::make('atifeenjubayer123'),
                'role' => 'user',
                'bio' => 'I love reading fantasy and science fiction novels. Always looking for my next great read!',
            ],[
                'first_name' => 'Ashraful',
                'last_name' => 'Hasan',
                'email' => 'ashrafulhasan@gmail.com',
                'password' => Hash::make('ashrafulhasan123'),
                'role' => 'user',
                'bio' => 'I love reading fantasy and science fiction novels. Always looking for my next great read!',
            ],
            [
                'first_name' => 'Masum',
                'last_name' => 'Molla',
                'email' => 'masummolla@gmail.com',
                'password' => Hash::make('masummolla123'),
                'role' => 'user',
                'bio' => 'I love reading fantasy and science fiction novels. Always looking for my next great read!',
            ],
        ];

        foreach ($users as $userData) {
            User::create($userData);
        }
    }
}