<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $input = ['password' => '123123'];

        $admin = User::updateOrCreate([
            'username' => 'adminz',
            'email' => 'adminz@example.com',
        ], $input);

        $user = User::updateOrCreate([
            'first_name' => 'User',
            'last_name' => 'Test',
            'username' => 'user1',
            'email' => 'user1@example.com',
        ], $input);

        $roleAdmin = Role::updateOrCreate(['name' => 'admin']);
        $roleUser = Role::updateOrCreate(['name' => 'user']);

        Permission::updateOrCreate(['name' => 'users.view']);
        Permission::updateOrCreate(['name' => 'users.create']);
        Permission::updateOrCreate(['name' => 'users.update']);
        Permission::updateOrCreate(['name' => 'users.delete']);
        Permission::updateOrCreate(['name' => 'roles.view']);
        Permission::updateOrCreate(['name' => 'roles.create']);
        Permission::updateOrCreate(['name' => 'roles.update']);
        Permission::updateOrCreate(['name' => 'roles.delete']);
        Permission::updateOrCreate(['name' => 'permissions.view']);
        Permission::updateOrCreate(['name' => 'permissions.create']);
        Permission::updateOrCreate(['name' => 'permissions.update']);
        Permission::updateOrCreate(['name' => 'permissions.delete']);

        $allPermissions = Permission::get();
        $roleAdmin->syncPermissions($allPermissions);

        $viewOnly = Permission::where('name', 'LIKE', '%.view')->get();
        $roleUser->syncPermissions($viewOnly);

        $user->assignRole($roleUser);
        $admin->assignRole($roleAdmin);

    }
}
