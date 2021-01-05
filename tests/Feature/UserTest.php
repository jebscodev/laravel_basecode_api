<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Database\Eloquent\Factories\Factory;
use Tests\TestCase;
use App\User;

class UserTest extends TestCase
{
    // can't refresh db due to passport
    // use RefreshDatabase;

    /**
     * Test register new user
     * 
     * @return void
     */
    public function testRegister()
    {
        $user = factory(User::class)->make();
        $payload = [
            'name' => $user->name,
            'email' => $user->email,
            'password' => $user->password,
            'password_confirmation' => $user->password,
            'email_verified_at' => null,
            'remember_token' => null,
        ];

        $response = $this->json('POST', '/api/register', $payload);
        $response->assertStatus(201)
            ->assertJsonStructure([
                'user',
                'access_token'
            ]);
    }

    /**
     * Test login
     *
     * @return void
     */
    public function testLogin()
    {
        $payload = [
            'email' => 'admin@test.com',
            'password' => 'admin12345'
        ];

        $response = $this->json('POST', '/api/login', $payload);
        $response->assertStatus(200)
            ->assertJsonStructure([
                'user',
                'access_token'
            ]);
    }

    /**
     * Test logout
     * 
     * @return void
     */
}
