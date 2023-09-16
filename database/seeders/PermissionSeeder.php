<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            // posts (news) crud
            ["posts management", "cms"],
            
            // categories crud
            ["categories management", "cms"],

            // access permission
            ["user management", "cms"],
            ["role management", "cms"],
            ["permission management", "cms"],

            // manage profile
            ["edit profile", "web"],
            ["change password", "web"],

            // can comment or not
            ["comment", "web"],
        ];

        for ($i = 0; $i < count($permissions); $i++) {
            Permission::create([
                "name" => $permissions[$i][0],
                "guard_name" => $permissions[$i][1],
            ]);
        }
    }
}
