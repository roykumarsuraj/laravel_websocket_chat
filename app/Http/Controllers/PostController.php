<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;

class PostController extends Controller
{

    public function dologin(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $username = $request->input('username');
        $password = $request->input('password');

        $user = DB::table('users')->where('uid', $username)->first();

        if (!$user || $user->pass !== $password) {
            return back()->with('error', 'Invalid ID or Password');
        }

        session([
            'fullname' => $user->full_name,
            'uid' => $user->uid
        ]);

        // configureable 
        Auth::loginUsingId($user->uid);

        return view('home');

    }

}