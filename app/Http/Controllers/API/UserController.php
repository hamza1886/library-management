<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Get logged in user info
     *
     * @param Request $request
     * @return User|mixed
     */
    public function show(Request $request)
    {
        return $request->user();
    }
}
