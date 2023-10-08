<?php

namespace Tests\Feature\API\Role;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\API\TenantTestCase;

class DeleteRoleTest extends TenantTestCase
{
    use DatabaseMigrations;

    public function test_delete_role() {
        $user = User::find(1);
        Sanctum::actingAs($user);
        $role = Role::create([
            'name' => $this->faker->name(),
            'ident' => $this->faker->userName(),
            'description' => $this->faker->text()
        ]);
        $role->permissions()->sync([1, 2, 3]);
        $response = $this->deleteJson("/api/v1/$this->username/roles/$role->id");
        $this->assertDatabaseMissing('roles', [
            'id' => $role->id,
            'name' => $role->name,
            'ident' => $role->ident,
            'description' => $role->description,
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
            ->where('message', "Role $role->id deleted")
            ->where('data', null));
    }

    public function test_delete_role_wrong_id()
    {
        $user = User::find(1);
        Sanctum::actingAs($user);
        $response = $this->deleteJson("/api/v1/$this->username/roles/2");
        $response->assertJson(fn(AssertableJson $json) => $json
            ->where('status', 'Error')
            ->where('message', "No query results for model [App\Models\Role] 2")
            ->where('data', null)
            ->etc());
    }
}
