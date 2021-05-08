<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function testRequiredFieldsForRegistration()
    {
        $response = $this->json('POST', 'api/register', [], ['Accept' => 'application/json']);
        $response->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'name' => ['The name field is required.'],
                    'email' => ['The email field is required.'],
                    'password' => ['The password field is required.'],
                    'date_of_birth' => ['The date of birth field is required.'],
                ]
            ]);
    }

    public function testRepeatPassword()
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'Demo12345',
            'date_of_birth' => '1970-01-01',
        ];

        $response = $this->json('POST', 'api/register', $userData, ['Accept' => 'application/json']);
        $response->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'password' => ['The password confirmation does not match.']
                ]
            ]);
    }

    public function testSuccessfulRegistration()
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'Demo12345',
            'password_confirmation' => 'Demo12345',
            'date_of_birth' => '1970-01-01',
        ];

        $response = $this->json('POST', 'api/register', $userData, ['Accept' => 'application/json']);
        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'user' => [
                        'id',
                        'name',
                        'email',
                        'date_of_birth',
                        'created_at',
                        'updated_at',
                    ],
                ],
                'message',
            ]);
    }

    public function testMustEnterEmailAndPassword()
    {
        $response = $this->json('POST', 'api/login');
        $response->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'email' => ['The email field is required.'],
                    'password' => ['The password field is required.'],
                    'device_name' => ['The device name field is required.'],
                ],
            ]);
    }

    public function testLoginWithWrongCredential()
    {
        $userData = [
            'email' => 'john@example.com',
            'password' => 'Demo12345',
            'device_name' => 'Home Computer',
        ];

        $response = $this->json('POST', 'api/login', $userData, ['Accept' => 'application/json']);
        $response->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'email' => ['The provided credentials are incorrect.'],
                ]
            ]);
    }

    public function testSuccessfulLogin()
    {
        ['user' => $user, 'response' => $response, 'access_token' => $access_token] = $this->loginUser();

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'user' => [
                        'id',
                        'name',
                        'email',
                        'email_verified_at',
                        'date_of_birth',
                        'created_at',
                        'updated_at',
                    ],
                    'access_token',
                ],
                'message',
            ]);

        $this->assertAuthenticated();
    }

    public function testSuccessfulLogout()
    {
        ['user' => $user, 'response' => $response, 'access_token' => $access_token] = $this->loginUser();

        $response = $this->json('POST', 'api/logout', [], ['Accept' => 'application/json', 'Authorization' => 'Bearer ' . $access_token]);
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'message',
            ]);
    }
}
