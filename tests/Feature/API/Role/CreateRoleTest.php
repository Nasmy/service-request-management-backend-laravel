<?php

namespace Tests\Feature\API\Role;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\API\TenantTestCase;

class CreateRoleTest extends TenantTestCase
{
    use DatabaseMigrations;

    public function test_create_role()
    {
        $user = User::find(1);
        Sanctum::actingAs($user);
        $name = $this->faker->name();
        $ident = $this->faker->userName();
        $description = $this->faker->text();
        $response = $this->postJson("/api/v1/$this->username/roles", [
            'name' => $name,
            'ident' => $ident,
            'description' => $description,
            'permissions' => [
                1,
                2,
                3
            ]
        ]);
        $this->assertDatabaseHas('roles', [
            'name' => $name,
            'ident' => $ident,
            'description' => $description
        ]);
        $role = Role::where('name', $name)->firstOrFail();
        $this->assertDatabaseHas('role_permissions', [
            'role_id' => $role->id,
            'permission_id' => 1
        ]);
        $this->assertDatabaseHas('role_permissions', [
            'role_id' => $role->id,
            'permission_id' => 2
        ]);
        $this->assertDatabaseHas('role_permissions', [
            'role_id' => $role->id,
            'permission_id' => 3
        ]);
        $response->assertJson(fn(AssertableJson $json) => $json
            ->where('status', 'Success')
            ->where('message', "Role $role->id created")
            ->has('data', fn(AssertableJson $json) => $json
                ->where('id', $role->id)
                ->where('name', $name)
                ->where('ident', $ident)
                ->where('description', $description)
                ->where('active', true)
                ->etc()));
    }

    public function test_create_role_missing_parameters()
    {
        $user = User::find(1);
        Sanctum::actingAs($user);
        $response = $this->postJson("/api/v1/$this->username/roles");
        $response->assertJson(fn(AssertableJson $json) => $json
            ->where('status', 'Error')
            ->where('message', 'The given data was invalid.')
            ->has('data', fn(AssertableJson $json) => $json
                ->where('name.0', 'Name is required!')
                ->where('ident.0', 'Ident is required!')
                ->where('description.0', 'Description is required!')
                ->where('permissions.0', 'Permissions is required!'))
            ->etc());
    }

    public function test_create_role_duplicate_parameters()
    {
        $user = User::find(1);
        Sanctum::actingAs($user);
        $description = $this->faker->text();
        $response = $this->postJson("/api/v1/$this->username/roles", [
            'name' => 'Admin',
            'ident' => 'administrator',
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

    public function test_create_role_wrong_parameters_type()
    {
        $user = User::find(1);
        Sanctum::actingAs($user);
        $response = $this->postJson("/api/v1/$this->username/roles", [
            'name' => $this->faker->numberBetween(),
            'ident' => $this->faker->numberBetween(),
            'description' => $this->faker->numberBetween(),
            'permissions' => $this->faker->numberBetween()
        ]);
        $response->assertJson(fn(AssertableJson $json) => $json
            ->where('status', 'Error')
            ->where('message', 'The given data was invalid.')
            ->has('data', fn(AssertableJson $json) => $json
                ->where('name.0', 'Name is a string!')
                ->where('ident.0', 'Ident is a string!')
                ->where('description.0', 'Description is a string!')
                ->where('permissions.0', 'Permissions is an array!'))
            ->etc());
    }

    public function test_create_role_wrong_permission_type()
    {
        $user = User::find(1);
        Sanctum::actingAs($user);
        $response = $this->postJson("/api/v1/$this->username/roles", [
            'name' => $this->faker->name(),
            'ident' => $this->faker->userName(),
            'description' => $this->faker->text(),
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

    public function test_create_role_wrong_permission_id()
    {
        $user = User::find(1);
        Sanctum::actingAs($user);
        $response = $this->postJson("/api/v1/$this->username/roles", [
            'name' => $this->faker->name(),
            'ident' => $this->faker->userName(),
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
