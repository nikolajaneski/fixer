<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request)
    {

        try {

            $data = $this->validate($request, [
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:8',
            ]);

            $data['password'] = bcrypt($request->password);

            $user = User::create($data);

            return response()->json([
                            'token' => $user->createToken('Laravel Personal Access Client')->accessToken
                        ],
                        200
                    );

        } catch (\Exception $e) {

            return response()->json([
                            'error' => $e->getMessage()
                        ],
                        400
                    );

        }

    }

    public function login(Request $request) 
    {

        $data = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if(auth()->attempt($data)) {

            $token = auth()->user()->createToken('Laravel Personal Access Client')->accessToken;

            return response()->json([
                            'token' => $token
                        ], 
                        200
                    );

        } else {

            return response()->json([
                            'error' => 'Access Denied'
                        ], 
                        401
                    );

        }
    }
}
