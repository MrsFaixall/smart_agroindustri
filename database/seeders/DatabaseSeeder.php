<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = [
            // Admin
            ['name' => 'Admin', 'email' => 'admin@gmail.com', 'role' => 'admin', 'password' => 'adminst'],
            ['name' => 'Admin 1', 'email' => 'admin1@gmail.com', 'role' => 'admin', 'password' => 'adminst'],
            
            // Super Admin
            ['name' => 'Super Admin', 'email' => 'superadmin@gmail.com', 'role' => 'admin', 'password' => 'superadminst'],
            ['name' => 'Super Admin 1', 'email' => 'superadmin1@gmail.com', 'role' => 'admin', 'password' => 'superadminst'],
            
            // Koperasi
            ['name' => 'Faisal Koperasi', 'email' => 'faisal@gmail.com', 'role' => 'koperasi', 'password' => 'faisalst'],
            ['name' => 'Koperasi', 'email' => 'koperasi@gmail.com', 'role' => 'koperasi', 'password' => 'koperasist'],
            
            // Petani
            ['name' => 'Habibi Petani', 'email' => 'habibi@gmail.com', 'role' => 'petani', 'password' => 'habibist'],
            ['name' => 'Petani', 'email' => 'petani@gmail.com', 'role' => 'petani', 'password' => 'petanist'],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'role' => $user['role'],
                    'password' => Hash::make($user['password']),
                ]
            );
        }
    }

}
