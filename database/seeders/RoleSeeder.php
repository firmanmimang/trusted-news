<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $super_admin = Role::firstOrCreate([
            'name' => 'super admin',
            'guard_name' => 'cms',
        ]);

        Role::create([
            'name' => 'admin',
            "guard_name" => 'cms',
        ]
        )->givePermissionTo([
            "posts management",
            "categories management",
            "user management",
        ]);

        Role::create([
            'name' => 'author',
            'guard_name' => 'cms',
        ])
        ->givePermissionTo([
            "posts management",
        ]);
        
        Role::create(['name' => 'subscriber']);

        User::create([
            'name'=> 'Firman Hidayat',
            'username'=> 'mimang',
            'email'=> 'fhidayat131@gmail.com',
            'password'=> bcrypt('password'),
        ])
        ->assignRole($super_admin)
        ->givePermissionTo([
            'comment',
            'edit profile',
            'change password',
        ]);
    }
}
