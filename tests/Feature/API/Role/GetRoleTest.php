<?php

namespace Tests\Feature\API\Role;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\API\TenantTestCase;

class GetRoleTest extends TenantTestCase
{
    use DatabaseMigrations;

    public function test_get_role()
    {
        $user = User::find(1);
        Sanctum::actingAs($user);
        $role = Role::create([
            'name' => $this->faker->name(),
            'ident' => $this->faker->userName(),
            'description' => $this->faker->text(),
            'active' => true
        ]);
        $response = $this->getJson("/api/v1/$this->username/roles/$role->id");
        $response->assertJson(fn(AssertableJson $json) => $json
            ->where('status', 'Success')
            ->where('message', "Details of role $role->id")
            ->has('data', fn(AssertableJson $json) => $json
                ->where('id', $role->id)
                ->where('name', $role->name)
                ->where('ident', $role->ident)
                ->where('description', $role->description)
                ->where('active', true)
                ->etc())
        );
    }

    public function test_get_role_wrong_id()
    {
        $user = User::find(1);
        Sanctum::actingAs($user);
        $response = $this->getJson("/api/v1/$this->username/roles/2");
        $response->assertJson(fn(AssertableJson $json) => $json
            ->where('status', 'Error')
            ->where('message', "No query results for model [App\Models\Role] 2")
            ->where('data', null)
            ->etc());
    }
}
