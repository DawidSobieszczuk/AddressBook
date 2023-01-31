<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Seeder;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleSeeder::class);

        User::create([
            'name' => 'admin',
            'password' => 'password',
            'email' => fake()->safeEmail(),
        ])->attachRole('admin');

        User::create([
            'name' => 'user1',
            'password' => 'password',
            'email' => fake()->safeEmail(),
        ])->attachRole('user');

        User::create([
            'name' => 'user2',
            'password' => 'password',
            'email' => fake()->safeEmail(),
        ])->attachRole('user');

        Address::factory()->count(10)->create(['user_id' => 1]);
        Address::factory()->count(5)->create(['user_id' => 2]);
        Address::factory()->count(4)->create(['user_id' => 3]);

        Address::factory()->count(1)->create(['user_id' => 3, 'city' => 'abc']);
        
    }
}
