<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Address;

class AddressTest extends TestCase
{
    use RefreshDatabase;

    private function getUser($isAdmin = false) {
        return User::find($isAdmin ? 1 : 2);
    }

    public function test_index()
    {
        $this->seed('TestSeeder');
        $url = 'api/addresses';

        $this->getJson($url)->assertStatus(401);

        Sanctum::actingAs($this->getUser());
        $this->getJson($url)->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->has(5)
        );

        Sanctum::actingAs($this->getUser(true));
        $this->getJson($url)->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->has(20)
        );

    }

    public function test_search()
    {
        $this->seed('TestSeeder');
        $url = 'api/addresses/search';
        $admin = $this->getUser(true);
        $user = $this->getUser(false);

        $data = [
            'search' => '',
            'limit' => 3,
        ];

        $this->postJson($url, $data)->assertStatus(401);

        // Admin
        Sanctum::actingAs($admin);
        $this->postJson($url, $data)->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->has(3)
        );

        $data = [
            'search' => 'abc',
            'limit' => 3,
        ];
        $this->postJson($url, $data)->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->has(1)
        );

        // User
        $data = [
            'search' => '',
            'limit' => -1,
        ];
        Sanctum::actingAs($user);
        $this->postJson($url, $data)->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->has(5)
        );

        $data = [
            'search' => 'abc',
            'limit' => 3,
        ];
        $this->postJson($url, $data)->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->has(0)
        );
    }

    public function test_store()
    {
        $this->seed('TestSeeder');
        $url = 'api/address';

        $admin = $this->getUser(true);
        $data = [
            'user_id' => $admin->id,
            'state' => 'state',
            'county' => 'county',
            'city' => 'city',
            'zip' => 'zip',
            'street' => 'street',
            'house_number' => '0',
            'latitude' => '0',
            'longitude' => '0',
        ];        

        $this->postJson($url, $data)->assertStatus(401);

        Sanctum::actingAs($admin);
        $this->postJson($url, $data)->assertStatus(201);
    }

    public function test_update()
    {
        $this->seed('TestSeeder');

        $url = 'api/address/1';
        
        $admin = $this->getUser(true);
        $user = $this->getUser();
        $data = ['city' => 'newCity'];

        $this->putJson($url, $data)->assertStatus(401);

        Sanctum::actingAs($admin);
        $this->putJson($url, $data)->assertStatus(200)
            ->assertJson($data);

        $data = [
            'user_id' => $admin->id,
            'state' => 'state',
            'county' => 'county',
            'city' => 'city',
            'zip' => 'zip',
            'street' => 'street',
            'house_number' => '0',
            'latitude' => '0',
            'longitude' => '0',
        ]; 

        $this->putJson($url, $data)->assertStatus(200)
            ->assertJson($data);

        // User
        Sanctum::actingAs($user);
        $this->putJson($url, $data)->assertStatus(403);

        $url = 'api/address/99';
        $this->putJson($url, $data)->assertStatus(404);
    }

    public function test_destroy()
    {
        $this->seed('TestSeeder');
        $url = 'api/address/';

        $user = $this->getUser();
        $admin = $this->getUser(true);
        $userAddress = Address::factory()->create(['user_id' => $user->id]);
        $adminAddress = Address::factory()->create(['user_id' => $admin->id]);

        $this->deleteJson($url . $userAddress->id)->assertStatus(401);
        $this->assertModelExists($userAddress);

        Sanctum::actingAs($user);
        $this->deleteJson($url . $userAddress->id)->assertStatus(200);
        $this->assertModelMissing($userAddress);

        $this->deleteJson($url . $adminAddress->id)->assertStatus(200);
        $this->assertModelExists($adminAddress);

        Sanctum::actingAs($admin);
        $userAddress = Address::factory()->create(['user_id' => $user->id]);
        $this->deleteJson($url . $userAddress->id)->assertStatus(200);
        $this->assertModelMissing($userAddress);
    }
}
