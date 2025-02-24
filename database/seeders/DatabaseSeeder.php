<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

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

            // DictionarySeeder::class,
        ]);

        Category::insert([
            [
                'name' => 'Ekonomi',
                'slug' => Str::slug('Ekonomi'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Hiburan',
                'slug' => Str::slug('Hiburan'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Hukum',
                'slug' => Str::slug('Hukum'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Politik',
                'slug' => Str::slug('Politik'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Teknologi',
                'slug' => Str::slug('Teknologi'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Olahraga',
                'slug' => Str::slug('Olahraga'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pendidikan',
                'slug' => Str::slug('Pendidikan'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kuliner',
                'slug' => Str::slug('Kuliner'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Otomotif',
                'slug' => Str::slug('Otomotif'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kesehatan',
                'slug' => Str::slug('Kesehatan'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
