<?php

namespace Tests\Feature\API;

use App\Models\User;
use Illuminate\Auth\Authenticatable;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

class AuthTest extends TenantTestCase
{
    use Authenticatable, DatabaseMigrations;

    public function test_unauthorized()
    {
        $response = $this->postJson("/api/v1/$this->username/auth");
        $response->assertJson(fn(AssertableJson $json) => $json
            ->where('status', 'Error')
            ->where('message', 'Invalid credentials.')
            ->where('data', null)
            ->where('code', 401));
    }

    public function test_login()
    {
        $user = User::find(1);
        $response = $this->actingAs($user)->postJson("/api/v1/$this->username/auth");
        $response->assertJson(fn(AssertableJson $json) => $json
            ->where('status', 'Success')
            ->where('message', 'Successfully logged in')
            ->has('data', fn(AssertableJson $json) => $json
                ->has('name')
                ->has('token', fn(AssertableJson $json) => $json
                    ->has('plainTextToken')
                    ->etc()))
        );
    }

    public function test_logout()
    {
        $user = User::find(1);
        Sanctum::actingAs($user);
        $response = $this->postJson("/api/v1/$this->username/logout");
        $response->assertJson(fn(AssertableJson $json) => $json
            ->where('status', 'Success')
            ->where('message', 'Successfully logged out')
            ->where('data', null)
        );
    }
}
