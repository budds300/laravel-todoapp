<?php

namespace Tests\Feature;

use App\Models\User;
use Faker\Factory;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use WithFaker;

    public function test_register_success()
    {
        // user details
        $password = '123456';
        $user = ['name' => $this->faker->name(), 'email' => $this->faker->email(), 'password' => $password, 'password_confirmation' => $password];

        // post
        $response = $this->postJson('api/auth/register', $user);

        // log
        $response->dump();

        // assert
        $response->assertStatus(200)->assertJsonStructure(['status', 'message', 'data']);
    }

    public function test_register_validation_error()
    {
        // user details
        $password = '123456';
        $user = ['name' => $this->faker->name(), 'email' => $this->faker->email(), 'password' => $password, 'password_confirmation' => ''];

        // post
        $response = $this->postJson('api/auth/register', $user);

        // log
        $response->dump();

        // assert
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('password');
        $this->assertGuest();
    }

    public function test_login_error()
    {
        // user details
        $user = ['email' => 'hpurdy@gmail.com', 'password' => '1234566'];

        // post
        $response = $this->postJson('api/auth/login', $user);

        // log
        $response->dump();

        // assert
        $response->assertStatus(401)->assertJsonStructure(['status', 'message', 'data']);
    }

    public function test_login_success()
    {
        // user details
        $user = ['email' => 'joannie.reinger@adams.org', 'password' => '123456'];

        // post
        $response = $this->postJson('api/auth/login', $user);

        // log
        $response->dump();
        $response->ddSession();

        // assert
        $response->assertStatus(200);
    }

    public function test_user()
    {
        // Authentication
        $user = ['email' => 'bayer.jerald@hotmail.com', 'password' => '123456'];
        $this->postJson('api/auth/login', $user);
        Sanctum::actingAs(auth()->user());

        // get
        $response = $this->getJson('/api/user');

        // log
        $response->dump();

        // assert
        $response->assertOk();
    }

    public function test_logout()
    {
        // Authentication
        $user = ['email' => 'bayer.jerald@hotmail.com', 'password' => '123456'];
        $this->postJson('api/auth/login', $user);
        Sanctum::actingAs(auth()->user());

        // // post - logout
        $response = $this->postJson('api/auth/logout');

        // // log
        $response->dump();

        // assert - check if token is still valid by trying to create a new task
        if (!$this->assertGuest()) {
            $task = ['text' => $this->faker->text(20), 'day' => $this->faker->date, 'reminder' => $this->faker->numberBetween(0, 1)];
            $response = $this->postJson('api/tasks', $task);

            $response->dump();

            $response->assertStatus(200);
        }
    }
}
