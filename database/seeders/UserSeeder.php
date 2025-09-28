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
                'first_name' => 'Admin',
                'last_name' => 'User',
                'email' => 'admin@goodreads.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'bio' => 'System administrator and book enthusiast.',
            ],
            [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'bio' => 'I love reading fantasy and science fiction novels. Always looking for my next great read!',
            ],
            [
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'email' => 'jane@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'bio' => 'Mystery and thriller books are my passion. I enjoy trying to solve the puzzle before the big reveal.',
            ],
            [
                'first_name' => 'Bob',
                'last_name' => 'Wilson',
                'email' => 'bob@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'bio' => 'Non-fiction reader focused on history, biography, and personal development books.',
            ],
            [
                'first_name' => 'Alice',
                'last_name' => 'Johnson',
                'email' => 'alice@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'bio' => 'Romance novel enthusiast who also enjoys contemporary fiction and memoirs.',
            ],
        ];

        foreach ($users as $userData) {
            User::create($userData);
        }
    }
}