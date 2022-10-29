<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\ReqisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use ApiResponse;

    public function register(ReqisterRequest $request)
    {
        try {
            $valid = $request->only('name', 'email', 'password');
            $valid['password'] = bcrypt($valid['password']);

            $user = User::create($valid);
            return $this->success($user, 'User registered successfully.');
        } catch (Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    public function login(LoginRequest $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return $this->error('Credentials not match', 401);
        }
        $request->session()->regenerate();
        return $this->success(auth()->user(), 'Login successful.');
    }

    public function logout(Request $request)
    {
        try {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return $this->success([], 'Successfully logged out.');
        } catch (Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }
}
