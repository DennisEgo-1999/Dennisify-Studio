<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    private array $roles = [
        [
            'name' => 'super_admin',
            'permissions' => [
                'impersonate',
                'manage-users',
                'manage-features',
                'access-dashboard',
            ]
        ],
        [
            'name' => 'admin',
            'permissions' => [
                'manage-users',
                'manage-features',
                'access-dashboard',
            ]
        ],
        [
            'name' => 'editor',
            'permissions' => [
                'manage-features',
                'access-dashboard',
            ]
        ],
        [
            'name' => 'visitor',
            'permissions' => []
        ],
    ];

    public function run()
    {
        foreach ($this->roles as $role) {
            //Creates roles
            $newRole = Role::updateOrCreate(
                ['name' => $role['name']]
            );

            //Creates permissions
            $permissions = $role['permissions'];

            foreach ($permissions as $permission) {
                $newPermission = Permission::firstOrCreate(['name' => $permission]);
                $newRole->givePermissionTo($newPermission);
            }
        }
    }
}
