<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['register', 'login']);
    }

    /**
     * Register a new user
     *
     * @param RegisterUserRequest $request
     * @return JsonResponse
     */
    public function register(RegisterUserRequest $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->date_of_birth = $request->date_of_birth;
        $user->save();

        return response()->json([
            'data' => [
                'user' => $user,
            ],
            'message' => 'Success registering user',
        ], 201);
    }

    /**
     * Login an existing user
     *
     * @param LoginUserRequest $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function login(LoginUserRequest $request)
    {
        $user = (new User)->where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return response()->json([
            'data' => [
                'user' => $user,
                'access_token' => $user->createToken($request->device_name)->plainTextToken,
            ],
            'message' => 'Success logging-in user',
        ]);
    }

    /**
     * Logout user from current device
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request)
    {
        $tokenId = explode('|', $request->bearerToken());
        $request->user()->tokens()->where('id', $tokenId)->delete();

        return response()->json([
            'data' => [],
            'message' => 'Success logout user from current device',
        ]);
    }

    /**
     * Logout user from all devices
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout_all(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'data' => [],
            'message' => 'Success logout user from all device',
        ]);
    }
}
