<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use DatabaseMigrations;

    //protected bool $seed = true;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_login()
    {
        //$this->seed();
        $response =  $this->post(route('authenticate'),[
                'username' => 'superadmin',
                'password' => 'DHs6MXun$U}kG3@U'
        ]);
        $response->assertRedirect(route('dashboard.index'));

        // checking after login is it redirecting to dashboard
        // if fails it will throw error
    }


    public function test_user_login_is_not_valid()
    {
        //$this->seed();
        $response =  $this->post(route('authenticate'),[
                'username' => 'superadmin',
                'password' => 'd$U}kG3@U'
        ]);
        $response->assertUnauthorized();

        // checking after login is it redirecting to dashboard
        // if fails it will throw error
    }


}
