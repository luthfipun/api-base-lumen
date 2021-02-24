<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request) {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:5'
        ]);

        if (!$token = Auth::attempt($request->only(['email', 'password']))){
            return $this->respondErrorWithMessage('email or password is incorrect');
        }

        $user = User::where('email', $request->get('email'))
            ->first();

        $user->token = $token;

        return $this->respondOkWithData(array($user));
    }

    public function register(Request $request){
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:5'
        ]);

        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password'))
        ]);

        $credentials = $request->only(['email', 'password']);

        if (!$token = Auth::attempt($credentials)){
            return $this->respondErrorWithMessage('email or password is incorrect');
        }

        $user->token = $token;

        return $this->respondOkWithData(array($user));
    }

    public function logout() {
        Auth::logout();
        return $this->respondSuccess();
    }
}
