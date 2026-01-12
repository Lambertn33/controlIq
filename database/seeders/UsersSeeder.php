<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@controliq.com',
                'password' => Hash::make('password'),
                'role' => User::ADMIN,
            ],
            [
                'name' => 'User',
                'email' => 'user@controliq.com',
                'password' => Hash::make('password'),
                'role' => User::USER,
            ],
        ];
        foreach ($users as $user) {
            User::create($user);
        }
    }
}
