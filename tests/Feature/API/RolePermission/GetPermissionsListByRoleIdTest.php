<?php

namespace Tests\Feature\API\RolePermission;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\API\TenantTestCase;

class GetPermissionsListByRoleIdTest extends TenantTestCase
{
    use DatabaseMigrations;

    public function test_get_permissions_list_by_role_id()
    {
        $user = User::find(1);
        Sanctum::actingAs($user);
        $role = Role::create([
            'name' => $this->faker->name(),
            'ident' => $this->faker->userName(),
            'description' => $this->faker->text()
        ]);
        $role->permissions()->sync([1, 2, 3]);
        $response = $this->getJson("/api/v1/$this->username/roles/$role->id/permissions");
        $response->assertJson(fn(AssertableJson $json) => $json
            ->where('status', 'Success')
            ->where('message', "List of permissions for role $role->id")
            ->has('data', 3)
            ->has('data.0', fn(AssertableJson $json) => $json
                ->where('id', 1)
                ->has('name')
                ->has('ident')
                ->has('description')
                ->has('active')
                ->etc())
            ->has('data.1', fn(AssertableJson $json) => $json
                ->where('id', 2)
                ->has('name')
                ->has('ident')
                ->has('description')
                ->has('active')
                ->etc())
            ->has('data.2', fn(AssertableJson $json) => $json
                ->where('id', 3)
                ->has('name')
                ->has('ident')
                ->has('description')
                ->has('active')
                ->etc()));
    }

    public function test_get_permissions_list_by_role_id_wrong_id()
    {
        $user = User::find(1);
        Sanctum::actingAs($user);
        $response = $this->getJson("/api/v1/$this->username/roles/2/permissions");
        $response->assertJson(fn(AssertableJson $json) => $json
            ->where('status', 'Error')
            ->where('message', "No query results for model [App\Models\Role].")
            ->where('data', null)
            ->etc());
    }
}
