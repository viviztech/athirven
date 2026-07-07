<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RolesAndPermissionsSeeder::class);

        $admin = User::firstOrCreate(
            ['email' => 'admin@athirven.test'],
            ['name' => 'Editor In Chief', 'password' => bcrypt('password')]
        );
        $admin->assignRole('Admin');

        $this->call(DemoContentSeeder::class);
    }
}
