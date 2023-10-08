<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Authenticatable;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class CreateTenantTest extends TestCase
{
    use Authenticatable, WithFaker, DatabaseMigrations;

    /**
     * A tenant create page can access.
     *
     * @return void
     */
    public function test_can_access_tenant_create_page()
    {
        $superadmin = User::find(1);
        $response = $this->actingAs($superadmin,'web')->get(route('dashboard.index'));
        $response->assertOk();

    }

    /**
     * A basic tenant create
     *
     * @return void
     */
    public function test_tenant_create()
    {
        Session::start();
        $tenant = [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->email,
            'mobile' => $this->faker->phoneNumber,
            'username' => $this->faker->userName(),
            'password' => 'unittestnew',
            'password_confirmation' => 'unittestnew',
            'zip' => $this->faker->postcode(),
            'role_id' => '2',
            'address' => $this->faker->address(),
            'organization' => $this->faker->company,
            'city' => $this->faker->city(),
            '_token' => Session::token()
        ];
        $response = $this->actingAs(User::find(1),'web')->post(route('user.store'), $tenant);
        $response->assertRedirect(route('dashboard.index'));
     }

     /**
     * A basic tenant create fail
     * if $tenant
     *
     * @return void
     */
    public function test_tenant_on_create_fail()
    {
        Session::start();
        $tenant = [
            'first_name' => 'test unit',
            'last_name' => 'test last unit',
            'email' => '1tests@test.com',
            'mobile' => '01131233516',
            'username' => '1tsest12',
            'password' => 'test1234',
            'password_confirmation' => 'test1234',
            'zip' => '1234',
            'role_id' => '2',
            'address' => 'new address',
            'organization' => 'organization',
            'city' => 'city new',
            '_token' => Session::token()
        ];
        $response = $this->actingAs(User::find(1),'web')->post(route('user.store'), $tenant);
        $response->assertSessionHasErrors();
    }
}
