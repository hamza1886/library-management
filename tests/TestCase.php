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
            'email' => 'sample@test.com',
            'password' => Hash::make('sample123'),
        ]);

        Sanctum::actingAs($user, ['*']);

        $loginData = [
            'email' => 'sample@test.com',
            'password' => 'sample123',
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
