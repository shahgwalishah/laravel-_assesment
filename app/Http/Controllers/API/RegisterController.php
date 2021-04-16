<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class RegisterController extends Controller
{
    public function store(Request $request){
        try {
            $check = $this->requestValidation($request);
            if(isset($check) && !$check['status']) {
                return $check;
            }
            if($this->checkUserEmailExists($request->email)) {
                return collect([
                    'status' => false,
                    'message' => 'email already exists'
                ]);
            }
            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'registered_at' => Carbon::now()
            ]);
            $token = $user->createToken('access_token')->accessToken;
            return  collect([
                'status' => true,
                'token' => $token,
                'user' => $user,
                'message' => 'user registered successfully ... !'
            ]);
        } catch (\Exception $e) {
            return collect([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function checkUserEmailExists($email){
        return  User::where('email','=',$email)->exists();
    }

    public function requestValidation($request){
        if(!$request->email) {
            return collect([
                'status' => false,
                'message' => 'Email Required'
            ]);
        }
        if(!$request->username) {
            return collect([
                'status' => false,
                'message' => 'Username Required'
            ]);
        }
        if(!$request->password) {
            return collect([
                'status' => false,
                'message' => 'Password Required'
            ]);
        }
    }
}
