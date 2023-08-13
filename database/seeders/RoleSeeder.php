<?php

namespace Database\Seeders;

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
        Role::create(['name' => 'super admin']);

        Role::create(['name' => 'admin'])->givePermissionTo([
            "posts management",
            
            "categories management",

            "users management",

            "edit profile",
            "change password",

            "comment",
        ]);

        Role::create(['name' => 'author'])->givePermissionTo([
            "posts management",
        ]);
        
        Role::create(['name' => 'subscriber']);
    }
}
