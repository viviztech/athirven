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

        $roleUsers = [
            'editor-in-chief@athirven.test' => ['Editor-in-Chief', 'Editor In Chief (test)'],
            'sub-editor@athirven.test' => ['Sub-Editor', 'Sub Editor (test)'],
            'writer@athirven.test' => ['Writer', 'Writer (test)'],
            'proofreader@athirven.test' => ['Proofreader', 'Proofreader (test)'],
            'designer@athirven.test' => ['Designer', 'Designer (test)'],
        ];

        foreach ($roleUsers as $email => [$role, $name]) {
            $user = User::firstOrCreate(
                ['email' => $email],
                ['name' => $name, 'password' => bcrypt('password')]
            );
            $user->syncRoles([$role]);
        }

        $this->call(DemoContentSeeder::class);
    }
}
