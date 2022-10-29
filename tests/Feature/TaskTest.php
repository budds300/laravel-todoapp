<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use WithFaker;


    public function test_index()
    {
        $response = $this->getJson('api/tasks');
        $response->decodeResponseJson();

        $response->assertStatus(200);
    }

    public function test_store()
    {
        // Authentication
        $user = ['email' => 'bayer.jerald@hotmail.com', 'password' => '123456'];
        $this->postJson('api/auth/login', $user);
        Sanctum::actingAs(auth()->user());

        // post
        $task = ['text' => $this->faker->text(20), 'day' => $this->faker->date, 'reminder' => $this->faker->numberBetween(0, 1)];
        $response = $this->postJson('api/tasks', $task);

        // log
        // $response->dd();

        // assert
        $response->assertOk(200);
    }

    public function test_show()
    {
        $response = $this->getJson('api/tasks/3');
        $response->dd();

        $response->assertStatus(200)
            ->assertJson(['message' => 'success']);
    }

    public function test_update()
    {
        // Authentication
        $user = ['email' => 'bayer.jerald@hotmail.com', 'password' => '123456'];
        $this->postJson('api/auth/login', $user);
        Sanctum::actingAs(auth()->user());

        // put
        $response = $this->putJson('api/tasks/5', ['text' => $this->faker->text(20), 'day' => $this->faker->date, 'reminder' => $this->faker->numberBetween(0, 1)]);

        // log
        // $response->dd();

        //assert
        $response->assertStatus(200);
    }

    public function test_destroy()
    {
        // Authentication
        $user = ['email' => 'bayer.jerald@hotmail.com', 'password' => '123456'];
        $this->postJson('api/auth/login', $user);
        Sanctum::actingAs(auth()->user());

        // delete
        $response = $this->deleteJson('api/tasks/3');

        // log
        // $response->dd();

        // assert
        $response->assertStatus(200);
    }
}
