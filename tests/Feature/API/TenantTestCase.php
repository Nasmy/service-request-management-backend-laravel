<?php

namespace Tests\Feature\API;

use App\Models\Role;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Stancl\Tenancy\Exceptions\TenantCouldNotBeIdentifiedById;
use Tests\CreatesApplication;
use Tests\TestCase;

abstract class TenantTestCase extends TestCase
{
    use CreatesApplication, WithFaker;

    protected string $username;
    protected Tenant $tenant;

    /**
     * @throws TenantCouldNotBeIdentifiedById
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->username = $this->faker()->userName();
        $password = $this->faker()->password();
        $data = [
            'first_name' => $this->faker()->firstName(),
            'last_name' => $this->faker()->lastName(),
            'email' => $this->faker()->email(),
            'mobile' => $this->faker()->phoneNumber(),
            'username' => $this->username,
            'password' => $password,
            'password_confirmation' => $password,
            'zip' => $this->faker()->postcode(),
            'role_id' => Role::DEFAULT_TENANT_ROLE_ID,
            'address' => $this->faker()->address(),
            'organization' => $this->faker()->company(),
            'city' => $this->faker()->city(),
        ];
        $user = User::create($data);
        $this->tenant = Tenant::create([
            'id' => $this->username,
            'user_id' => $user->id,
            'first_name' => $this->faker()->firstName(),
            'last_name' => $this->faker()->lastName(),
            'email' => $this->faker()->email(),
            'mobile' => $this->faker()->phoneNumber(),
            'username' => $this->username,
            'password' => $password,
            'zip' => $this->faker()->postcode(),
            'role_id' => Role::DEFAULT_ADMIN_ROLE_ID,
            'is_admin' => Role::DEFAULT_ADMIN_ROLE_ID,
            'address' => $this->faker()->address(),
            'city' => $this->faker()->city(),
        ]);
        tenancy()->initialize($this->tenant);
    }

    protected function tearDown(): void
    {
        $this->tenant->delete();
    }
}
