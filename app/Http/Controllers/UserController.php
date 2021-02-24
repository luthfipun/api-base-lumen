<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function me() {
        $id = Auth::id();

        $data = User::where('id', $id)->first();
        return $this->respondOkWithData($data);
    }
}
