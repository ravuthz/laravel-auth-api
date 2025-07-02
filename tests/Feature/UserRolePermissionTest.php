<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Database\Seeders\ClientSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class UserRolePermissionTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        // Reset cached for laravel roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $this->seed(ClientSeeder::class);
        $this->seed(UserSeeder::class);

//        dd(User::get()->pluck('username')->toArray());
    }

    /**
     * A basic feature test example.
     */
    public function test_user_role_permission_exists(): void
    {
        $this->assertDatabaseHas('users', [
            'username' => ['adminz', 'user1'],
        ]);

        $this->assertDatabaseHas('roles', [
            'name' => ['admin', 'user'],
        ]);

        $this->assertDatabaseHas('permissions', [
            'name' => ['users.view', 'users.create', 'users.update', 'users.delete'],
        ]);

        if (DB::getDriverName()) {
            $this->assertTrue(Permission::where('name', 'LIKE', 'users.%')->exists());
            $this->assertTrue(Permission::where('name', 'LIKE', 'roles.%')->exists());
            $this->assertTrue(Permission::where('name', 'LIKE', 'permissions.%')->exists());
        } else {
            $this->assertTrue(
                Permission::where('name', '~', '^users|roles|permissions')->exists(),
            );
        }
    }

    public function test_admin_has_role_permission(): void
    {
        $admin = User::where('username', 'adminz')->first();

        $this->assertTrue($admin->hasRole('admin'));

        $this->assertTrue($admin->can('users.view'));
        $this->assertTrue($admin->can('users.create'));
        $this->assertTrue($admin->can('users.update'));
        $this->assertTrue($admin->can('users.delete'));
    }

    public function test_user_has_role_permission(): void
    {
        $admin = User::where('username', 'user1')->first();

        $this->assertTrue($admin->hasRole('user'));
        $this->assertTrue($admin->hasPermissionTo('users.view'));

        $this->assertTrue($admin->cannot('users.create'));
        $this->assertTrue($admin->cannot('users.update'));
        $this->assertTrue($admin->cannot('users.delete'));
    }
}
