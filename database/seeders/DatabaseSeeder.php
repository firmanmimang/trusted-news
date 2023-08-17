<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
        ]);

        User::create([
            'name'=> 'Firman Hidayat',
            'username'=> 'mimang',
            'email'=> 'fhidayat131@gmail.com',
            'password'=> bcrypt('password'),
        ])
        ->assignRole('super admin')
        ->givePermissionTo(['comment', 'edit profile', 'change password', '']);

    }
}
