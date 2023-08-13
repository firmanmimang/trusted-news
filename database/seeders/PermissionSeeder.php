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
            "posts management",
            
            // categories crud
            "categories management",

            // users crud
            "users management",

            // manage profile
            "edit profile",
            "change password",

            // can comment or not
            "comment",
        ];

        for ($i = 0; $i < count($permissions); $i++) {
            Permission::create([
                "name" => $permissions[$i]
            ]);
        }
    }
}
