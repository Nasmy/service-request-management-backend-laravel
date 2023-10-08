<?php

namespace Tests\Feature\API\RolePermission;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\API\TenantTestCase;

class UpdatePermissionsByRoleId extends TenantTestCase
{
    use DatabaseMigrations;

    public function test_update_permissions_by_role_id() {
        $user = User::find(1);
        Sanctum::actingAs($user);
        $role = Role::create([
            'name' => $this->faker->name(),
            'ident' => $this->faker->userName(),
            'description' => $this->faker->text()
        ]);
        $role->permissions()->sync([1, 2, 3]);
        $response = $this->postJson("/api/v1/$this->username/roles/$role->id/permissions", [
            'permissions' => [
                4,
                5,
            ]
        ]);
        $this->assertDatabaseHas('role_permissions', [
            'role_id' => $role->id,
            'permission_id' => 4
        ]);
        $this->assertDatabaseHas('role_permissions', [
            'role_id' => $role->id,
            'permission_id' => 5
        ]);
        $this->assertDatabaseMissing('role_permissions', [
            'role_id' => $role->id,
            'permission_id' => 1
        ]);
        $this->assertDatabaseMissing('role_permissions', [
            'role_id' => $role->id,
            'permission_id' => 2
        ]);
        $this->assertDatabaseMissing('role_permissions', [
            'role_id' => $role->id,
            'permission_id' => 3
        ]);
        $response->assertJson(fn(AssertableJson $json) => $json
            ->where('status', 'Success')
            ->where('message', "Permissions updated for role $role->id")
            ->has('data', 2)
            ->has('data.0', fn(AssertableJson $json) => $json
                ->where('id', 4)
                ->has('name')
                ->has('ident')
                ->has('description')
                ->has('active')
                ->etc())
            ->has('data.1', fn(AssertableJson $json) => $json
                ->where('id', 5)
                ->has('name')
                ->has('ident')
                ->has('description')
                ->has('active')
                ->etc()));
    }

    public function test_update_permissions_by_role_id_wrong_id()
    {
        $user = User::find(1);
        Sanctum::actingAs($user);
        $response = $this->postJson("/api/v1/$this->username/roles/2/permissions", [
            'permissions' => [
                4,
                5,
            ]
        ]);
        $response->assertJson(fn(AssertableJson $json) => $json
            ->where('status', 'Error')
            ->where('message', "No query results for model [App\Models\Role].")
            ->where('data', null)
            ->etc());
    }

    public function test_update_permissions_by_role_id_missing_parameters()
    {
        $user = User::find(1);
        Sanctum::actingAs($user);
        $response = $this->postJson("/api/v1/$this->username/roles/1/permissions");
        $response->assertJson(fn(AssertableJson $json) => $json
            ->where('status', 'Error')
            ->where('message', 'The given data was invalid.')
            ->has('data', fn(AssertableJson $json) => $json
                ->where('permissions.0', 'Permissions is required!'))
            ->etc());
    }

    public function test_update_permissions_by_role_id_duplicate_parameters()
    {
        $user = User::find(1);
        Sanctum::actingAs($user);
        $response = $this->postJson("/api/v1/$this->username/roles/1/permissions", [
            'permissions' => [
                2,
                2,
                3
            ]
        ]);
        $response->assertJson(fn(AssertableJson $json) => $json
            ->where('status', 'Error')
            ->where('message', 'The given data was invalid.')
            ->has('data', 2)
            ->etc());
        $response->assertJsonFragment([
            'permissions.0' => [
                'Permissions must be different!'
            ],
            'permissions.1' => [
                'Permissions must be different!'
            ]
        ]);
    }

    public function test_update_permissions_by_role_id_wrong_parameters_type()
    {
        $user = User::find(1);
        Sanctum::actingAs($user);
        $response = $this->postJson("/api/v1/$this->username/roles/1/permissions", [
            'permissions' => $this->faker->numberBetween()
        ]);
        $response->assertJson(fn(AssertableJson $json) => $json
            ->where('status', 'Error')
            ->where('message', 'The given data was invalid.')
            ->has('data', fn(AssertableJson $json) => $json
                ->where('permissions.0', 'Permissions is an array!'))
            ->etc());
    }

    public function test_update_permissions_by_role_id_wrong_permission_type()
    {
        $user = User::find(1);
        Sanctum::actingAs($user);
        $response = $this->postJson("/api/v1/$this->username/roles/1/permissions", [
            'permissions' => [
                1,
                2,
                $this->faker->randomLetter()
            ]
        ]);
        $response->assertJson(fn(AssertableJson $json) => $json
            ->where('status', 'Error')
            ->where('message', 'The given data was invalid.')
            ->has('data', 1)
            ->etc());
        $response->assertJsonFragment([
            'permissions.2' => [
                'Permissions is an array of integers!'
            ]
        ]);
    }

    public function test_update_permissions_by_role_id_wrong_permission_id()
    {
        $user = User::find(1);
        Sanctum::actingAs($user);
        $response = $this->postJson("/api/v1/$this->username/roles/1/permissions", [
            'permissions' => [
                1,
                2,
                30
            ]
        ]);
        $response->assertJson(fn(AssertableJson $json) => $json
            ->where('status', 'Error')
            ->where('message', 'The given data was invalid.')
            ->has('data', 1)
            ->etc());
        $response->assertJsonFragment([
            'permissions.2' => [
                'Permission 30 does not exist!'
            ]
        ]);
    }
}
