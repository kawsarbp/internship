<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::create(['name' => 'admin']);
        $permission = Permission::create(['name' => 'all permissions']);

        $role->givePermissionTo($permission);
        $permission->assignRole($role);

        $user = User::find(1);
        $user->assignRole('admin'); // Assigns the role 'writer' to the user

        $role = Role::create(['name' => 'user']);
        $permission = Permission::create(['name' => 'access user dashboard']);

        $role->givePermissionTo($permission);
        $permission->assignRole($role);


        $user = User::find(1);
        $user->assignRole('user');
    }
}
