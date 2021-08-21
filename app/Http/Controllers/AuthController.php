<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // validate field
        $fields = $request->validate([
            'fullname' => 'required|string',
            'username' => 'required|string',
            'email'    => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed',
            'tel'      => 'required',
            'role'     => 'required|integer',
        ]);

        // encypt password
        $fields['password'] = bcrypt($fields['password']);

        // insert new user to database
        $user = User::create(
            $fields
        );

        // create token
        $token = $user->createToken( $request->userAgent(), ["$user->role"] )->plainTextToken;

        $response = [
            'message' => 'success',
            'func' => 'register',
            'user'  => $user,
            'token' => $token,
        ];

        return response($response, 201);
    }

    public function login(Request $request)
    {
        // validate field
        $fields = $request->validate([
            'email'    => 'required|string',
            'password' => 'required|string',
        ]);

        // check email
        $user = User::where('email', $fields['email'])->first();

        if( !$user || !Hash::check( $fields['password'], $user->password) ){ 
            // login fail
            return response([
                'message' => 'Invalid login'
            ], 401);
        }else{
            // login success
            $user->tokens()->delete(); // delete old token
            $token = $user->createToken( $request->userAgent(), ["$user->role"] )->plainTextToken;


            $response = [
                'message' => 'success',
                'func' => 'login',
                'user'  => $user,
                'token' => $token,
            ];

            return response($response, 201);
        }
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();
        $response = [
            'message'  => 'success',
            'func' => 'logout' 
        ];
        return response($response, 200);
    }
}
