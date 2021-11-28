<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Get logged in user info
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function show(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'data' => [
                'user' => $user,
            ],
            'message' => 'Success fetching users',
        ]);
    }
}
