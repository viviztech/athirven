<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'articles.create', 'articles.edit.own', 'articles.edit.any',
            'articles.submit', 'articles.review', 'articles.approve', 'articles.reject',
            'articles.schedule', 'articles.publish', 'articles.archive', 'articles.delete',
            'issues.manage',
            'media.upload',
            'comments.moderate',
            'users.manage',
            'subscriptions.manage',
            'ads.manage',
            'settings.manage',
            'authors.view-real-identity',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Admin — technical superuser, all permissions (also bypassed via Gate::before)
        $admin = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $admin->syncPermissions(Permission::all());

        // Editor-in-Chief — full editorial authority
        $eic = Role::firstOrCreate(['name' => 'Editor-in-Chief', 'guard_name' => 'web']);
        $eic->syncPermissions([
            'articles.create', 'articles.edit.own', 'articles.edit.any',
            'articles.submit', 'articles.review', 'articles.approve', 'articles.reject',
            'articles.schedule', 'articles.publish', 'articles.archive', 'articles.delete',
            'issues.manage', 'media.upload', 'comments.moderate',
            'subscriptions.manage', 'ads.manage', 'settings.manage',
            'authors.view-real-identity',
        ]);

        // Sub-Editor — reviews and schedules, no settings/subscriptions/ads
        $subEditor = Role::firstOrCreate(['name' => 'Sub-Editor', 'guard_name' => 'web']);
        $subEditor->syncPermissions([
            'articles.create', 'articles.edit.any',
            'articles.review', 'articles.approve', 'articles.reject',
            'articles.schedule', 'issues.manage', 'media.upload', 'comments.moderate',
        ]);

        // Writer — own drafts only
        $writer = Role::firstOrCreate(['name' => 'Writer', 'guard_name' => 'web']);
        $writer->syncPermissions([
            'articles.create', 'articles.edit.own', 'articles.submit', 'media.upload',
        ]);

        // Proofreader — reviews, no publish authority
        $proofreader = Role::firstOrCreate(['name' => 'Proofreader', 'guard_name' => 'web']);
        $proofreader->syncPermissions([
            'articles.review', 'comments.moderate',
        ]);

        // Designer — media/layout only, never article status
        $designer = Role::firstOrCreate(['name' => 'Designer', 'guard_name' => 'web']);
        $designer->syncPermissions([
            'media.upload', 'issues.manage',
        ]);

        // Subscriber — public-only, no panel access, no permissions
        Role::firstOrCreate(['name' => 'Subscriber', 'guard_name' => 'web']);

        $this->command->info('Roles and permissions seeded successfully.');
    }
}
