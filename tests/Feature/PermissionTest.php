<?php

namespace Tests\Feature;

use App\Enums\Permission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PermissionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A user with no permissions should not have any permission.
     */
    public function test_user_without_permission_returns_false(): void
    {
        $user = User::factory()->create();

        $this->assertFalse($user->hasPermission(Permission::USER));
        $this->assertFalse($user->hasPermission(Permission::ADD_GAME));
        $this->assertFalse($user->hasPermission(Permission::API));
        $this->assertFalse($user->hasPermission(Permission::STAFF));
        $this->assertFalse($user->hasPermission(Permission::ADMIN));
    }

    /**
     * A permission can be added to a user.
     */
    public function test_permission_can_be_added_to_user(): void
    {
        $user = User::factory()->create();

        $user->addPermission(Permission::USER);

        $this->assertTrue($user->hasPermission(Permission::USER));
    }

    /**
     * Adding a permission does not grant other permissions.
     */
    public function test_adding_one_permission_does_not_grant_others(): void
    {
        $user = User::factory()->create();

        $user->addPermission(Permission::STAFF);

        $this->assertTrue($user->hasPermission(Permission::STAFF));
        $this->assertFalse($user->hasPermission(Permission::USER));
        $this->assertFalse($user->hasPermission(Permission::ADD_GAME));
        $this->assertFalse($user->hasPermission(Permission::API));
        $this->assertFalse($user->hasPermission(Permission::ADMIN));
    }

    /**
     * Multiple permissions can be added to a user.
     */
    public function test_multiple_permissions_can_be_added_to_user(): void
    {
        $user = User::factory()->create();

        $user->addPermission(Permission::USER);
        $user->addPermission(Permission::ADD_GAME);
        $user->addPermission(Permission::API);

        $this->assertTrue($user->hasPermission(Permission::USER));
        $this->assertTrue($user->hasPermission(Permission::ADD_GAME));
        $this->assertTrue($user->hasPermission(Permission::API));
        $this->assertFalse($user->hasPermission(Permission::STAFF));
        $this->assertFalse($user->hasPermission(Permission::ADMIN));
    }

    /**
     * Adding the same permission twice does not duplicate it.
     */
    public function test_adding_duplicate_permission_does_not_create_duplicate(): void
    {
        $user = User::factory()->create();

        $user->addPermission(Permission::ADMIN);
        $user->addPermission(Permission::ADMIN);

        $this->assertTrue($user->hasPermission(Permission::ADMIN));
        $this->assertSame(1, $user->permissions()->count());
    }

    /**
     * All defined permission levels exist in the enum.
     */
    public function test_all_permission_levels_are_defined(): void
    {
        $cases = Permission::cases();
        $values = array_map(fn (Permission $p) => $p->value, $cases);

        $this->assertContains('USER', $values);
        $this->assertContains('ADD_GAME', $values);
        $this->assertContains('API', $values);
        $this->assertContains('STAFF', $values);
        $this->assertContains('ADMIN', $values);
    }

    /**
     * hasPermission works correctly for each individual permission level.
     */
    public function test_each_permission_level_can_be_validated(): void
    {
        $user = User::factory()->create();

        foreach (Permission::cases() as $permission) {
            $this->assertFalse($user->hasPermission($permission), "Expected no {$permission->value} permission before granting");

            $user->addPermission($permission);

            $this->assertTrue($user->hasPermission($permission), "Expected {$permission->value} permission after granting");
        }
    }
}
