<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use Laravel\Passport\Passport;

class CarTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetCarList()
    {
        // TODO, not yet working. still returns 404
        // basically due to the owner of the car being the logged in user
        Passport::actingAs(factory(User::class)->create());
        $response = $this->json('GET', '/api/cars/1');

        // $user = factory(User::class)->create();
        // $response = $this->actingAs($user)->json('GET', '/api/cars');

        // print_r($response);
        // exit;
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id', 'details', 'entries'
                ]
            ]);
    }
}
