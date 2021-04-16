<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login(Request $request) {
        try {
            $credentials = [
                'email' => $request->email,
                'password' => $request->password
            ];
            if (auth()->attempt($credentials)) {
                $token = auth()->user()->createToken('access_token')->accessToken;
                return response()->json(['user' => auth()->user() , 'token' => $token], 200);
            } else {
                return response()->json(['error' => 'UnAuthorised'], 401);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'error' => 'UnAuthorised'], 401);
        }
    }
}
