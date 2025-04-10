<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AssignRolesPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $this->rolesPermissions();

        // $role->
        // $role = Role::findByName('Super Admin');
        // $permissions = Permission::all();

        // $role->syncPermissions($permissions);

        // $role = Role::findByName('Marketing');

    }

    public function rolesPermissions()
    {
        $roles = $this->roles();
        $permissions = $this->permissions();

        $rolePermissions = [
            'Super Admin' => [
                $permissions['projects'],
                $permissions['active-projects'],
            ],
            'Marketing' => [
                $permissions['projects'],
            ],
            'TSS' => [
                $permissions['active-projects'],
            ],
            'Project Manager' => [
                $permissions['active-projects'],
            ],
            'Project Engineer' => [
                $permissions['active-projects'],
            ],
        ];

        foreach ($rolePermissions as $roleName => $groupPermissions) {
            $role = Role::findByName($roleName);

            if ($role && isset($rolePermissions[$roleName])) {
                $role->syncPermissions($groupPermissions);
            }
        }
    }

    public function roles()
    {
        $roles = [
            'Super Admin',
            'Marketing',
            'TSS',
            'Project Manager',
            'Project Engineer',
        ];

        foreach ($roles as $role) {

            Role::updateOrCreate([
                'name' => $role,
                'guard_name' => 'api',
            ]);
        }

        return $roles;
    }

    public function permissions()
    {
        $permissions = [
            'projects' => [
                'view-projects',
                'create-project',
                'edit-project',
                'approve-project',
                'archive-project',
                'award-project',
            ],
            'active-projects' => [
                'view-active-projects',
                'edit-active-projects',
                'approve-active-projects',
                'archive-active-project',
                'hold-active-project',
            ],

        ];

        foreach ($permissions as $group => $groupPermissions) {
            foreach ($groupPermissions as $permission) {
                Permission::updateOrCreate([
                    'name' => $permission,
                    'guard_name' => 'api',
                ]);

            }
        }

        return $permissions;
    }
}
