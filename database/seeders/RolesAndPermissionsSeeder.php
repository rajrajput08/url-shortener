<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    // role => permissions it gets. sales and manager have none for now.
    protected array $roles = [
        'super_admin' => ['invite admin to new company', 'view all short urls'],
        'admin' => ['invite company users', 'create short urls', 'view company short urls'],
        'member' => ['create short urls', 'view own short urls'],
        'sales' => [],
        'manager' => [],
    ];

    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach ($this->roles as $permissions) {
            foreach ($permissions as $permission) {
                Permission::findOrCreate($permission, 'web');
            }
        }

        foreach ($this->roles as $role => $permissions) {
            Role::findOrCreate($role, 'web')->syncPermissions($permissions);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
