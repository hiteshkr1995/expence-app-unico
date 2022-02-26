<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\User\RegisterRequest;
use App\Http\Requests\API\User\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function list(Request $request)
    {
        $users = User::latest()
        ->where('id', '!=', auth()->id())
        ->get();

        return response()->json([
            "messsage" => "Users list!",
            "data" => $users,
        ]);
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'mobile_no' => $request->mobile_no,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        $token = $user->createToken('default');

        return response()->json([
            "messsage" => "User registered sucessfully!",
            "auth_token" => $token->plainTextToken,
            "data" => $user,
        ], Response::HTTP_CREATED);
    }

    public function login(LoginRequest $request)
    {
        $request->authenticate();

        $user = $request->user();

        $token = $user->createToken('default');

        return response()->json([
            "messsage" => "User login sucessfully!",
            "auth_token" => $token->plainTextToken,
            "data" => $user,
        ]);
    }

    public function logout(Request $request)
    {
        // Revoke the token that was used to authenticate the current request.
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            "messsage" => "Logged out sucessfully!",
            "data" => null,
        ]);
    }
}
