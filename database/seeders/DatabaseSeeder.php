<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run migrations for spatie/permission
        $this->call([
            RolesAndPermissionsSeeder::class,
            ProjectSeeder::class,
            TeamMemberSeeder::class,
        ]);
    }
}
