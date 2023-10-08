<?php

namespace Tests\Feature\API\Role;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\API\TenantTestCase;

class GetRolesListTest extends TenantTestCase
{
    use DatabaseMigrations;

    public function test_get_roles_list()
    {
        $user = User::find(1);
        Sanctum::actingAs($user);
        $role = Role::create([
            'name' => $this->faker->name(),
            'ident' => $this->faker->userName(),
            'description' => $this->faker->text(),
            'active' => true
        ]);
        $response = $this->getJson("/api/v1/$this->username/roles");
        $response->assertJson(fn(AssertableJson $json) => $json
            ->where('status', 'Success')
            ->where('message', 'List of roles')
            ->has('data', 2)
            ->has('data.0', fn(AssertableJson $json) => $json
                ->where('id', 1)
                ->where('name', 'Admin')
                ->where('ident', 'administrator')
                ->where('description', 'admin')
                ->where('active', true)
                ->etc())
            ->has('data.1', fn(AssertableJson $json) => $json
                ->where('id', $role->id)
                ->where('name', $role->name)
                ->where('ident', $role->ident)
                ->where('description', $role->description)
                ->where('active', true)
                ->etc())
        );
    }
}
