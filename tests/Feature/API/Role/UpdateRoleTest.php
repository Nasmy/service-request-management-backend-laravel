<?php

namespace Tests\Feature\API\Role;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\API\TenantTestCase;

class UpdateRoleTest extends TenantTestCase
{
    use DatabaseMigrations;

    public function test_update_role() {
        $user = User::find(1);
        Sanctum::actingAs($user);
        $role = Role::create([
            'name' => $this->faker->name(),
            'ident' => $this->faker->userName(),
            'description' => $this->faker->text()
        ]);
        $role->permissions()->sync([1, 2, 3]);
        $new_name = $this->faker->name();
        $new_ident = $this->faker->userName();
        $new_description = $this->faker->text();
        $new_active = $this->faker->boolean();
        $response = $this->putJson("/api/v1/$this->username/roles/$role->id", [
            'name' => $new_name,
            'ident' => $new_ident,
            'description' => $new_description,
            'active' => $new_active,
            'permissions' => [
                4,
                5,
            ]
        ]);
        $this->assertDatabaseHas('roles', [
            'id' => $role->id,
            'name' => $new_name,
            'ident' => $new_ident,
            'description' => $new_description,
            'active' => $new_active
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
            ->where('message', "Role $role->id updated")
            ->has('data', fn(AssertableJson $json) => $json
                ->where('id', $role->id)
                ->where('name', $new_name)
                ->where('ident', $new_ident)
                ->where('description', $new_description)
                ->where('active', $new_active)
                ->etc()));
    }

    public function test_update_role_wrong_id()
    {
        $user = User::find(1);
        Sanctum::actingAs($user);
        $response = $this->putJson("/api/v1/$this->username/roles/2", [
            'name' => $this->faker->name(),
            'ident' => $this->faker->userName(),
            'description' => $this->faker->text(),
            'active' => $this->faker->boolean(),
            'permissions' => [
                1,
                2,
                3
            ]
        ]);
        $response->assertJson(fn(AssertableJson $json) => $json
            ->where('status', 'Error')
            ->where('message', "No query results for model [App\Models\Role] 2")
            ->where('data', null)
            ->etc());
    }

    public function test_update_role_missing_parameters()
    {
        $user = User::find(1);
        Sanctum::actingAs($user);
        $response = $this->putJson("/api/v1/$this->username/roles/1");
        $response->assertJson(fn(AssertableJson $json) => $json
            ->where('status', 'Error')
            ->where('message', 'The given data was invalid.')
            ->has('data', fn(AssertableJson $json) => $json
                ->where('name.0', 'Name is required!')
                ->where('ident.0', 'Ident is required!')
                ->where('active.0', 'Active is required!')
                ->where('description.0', 'Description is required!')
                ->where('permissions.0', 'Permissions is required!'))
            ->etc());
    }

    public function test_update_role_duplicate_parameters()
    {
        $user = User::find(1);
        Sanctum::actingAs($user);
        $description = $this->faker->text();
        $role = Role::create([
            'name' => $this->faker->name(),
            'ident' => $this->faker->userName(),
            'description' => $this->faker->text()
        ]);
        $response = $this->putJson("/api/v1/$this->username/roles/$role->id", [
            'name' => 'Admin',
            'ident' => 'administrator',
            'active' => $this->faker->boolean(),
            'description' => $description,
            'permissions' => [
                2,
                2,
                3
            ]
        ]);
        $response->assertJson(fn(AssertableJson $json) => $json
            ->where('status', 'Error')
            ->where('message', 'The given data was invalid.')
            ->has('data', fn(AssertableJson $json) => $json
                ->where('name.0', 'Name is unique!')
                ->where('ident.0', 'Ident is unique!')
                ->etc())
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

    public function test_update_role_wrong_parameters_type()
    {
        $user = User::find(1);
        Sanctum::actingAs($user);
        $response = $this->putJson("/api/v1/$this->username/roles/1", [
            'name' => $this->faker->numberBetween(),
            'ident' => $this->faker->numberBetween(),
            'active' => $this->faker->numberBetween(),
            'description' => $this->faker->numberBetween(),
            'permissions' => $this->faker->numberBetween()
        ]);
        $response->assertJson(fn(AssertableJson $json) => $json
            ->where('status', 'Error')
            ->where('message', 'The given data was invalid.')
            ->has('data', fn(AssertableJson $json) => $json
                ->where('name.0', 'Name is a string!')
                ->where('ident.0', 'Ident is a string!')
                ->where('active.0', 'Active is a boolean!')
                ->where('description.0', 'Description is a string!')
                ->where('permissions.0', 'Permissions is an array!'))
            ->etc());
    }

    public function test_update_role_wrong_permission_type()
    {
        $user = User::find(1);
        Sanctum::actingAs($user);
        $response = $this->putJson("/api/v1/$this->username/roles/1", [
            'name' => $this->faker->name(),
            'ident' => $this->faker->userName(),
            'description' => $this->faker->text(),
            'active' => $this->faker->boolean(),
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

    public function test_update_role_wrong_permission_id()
    {
        $user = User::find(1);
        Sanctum::actingAs($user);
        $response = $this->putJson("/api/v1/$this->username/roles/1", [
            'name' => $this->faker->name(),
            'ident' => $this->faker->userName(),
            'active' => $this->faker->boolean(),
            'description' => $this->faker->text(),
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
