<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function loginUser()
    {
        $user = User::factory()->create([
            'email' => 'john@example.com',
            'password' => Hash::make('Demo12345'),
        ]);

        Sanctum::actingAs($user, ['*']);

        $loginData = [
            'email' => 'john@example.com',
            'password' => 'Demo12345',
            'device_name' => 'Home Computer',
        ];

        $response = $this->json('POST', 'api/login', $loginData, ['Accept' => 'application/json']);

        $access_token = (json_decode($response->content()))->data->access_token;

        return [
            'user' => $user,
            'access_token' => $access_token,
        ];
    }
}
