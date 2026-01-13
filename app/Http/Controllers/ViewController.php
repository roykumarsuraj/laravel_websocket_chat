<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ViewController extends Controller
{

    public function index()
    {
        if (session()->has('uid')) {
            return redirect('/home');
        }
        return view('index');
    }

    public function home()
    {
        if (!session()->has('uid')) {
            return redirect('/index');
        }
        return view('home');
    }

    public function logout(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('index');
    }

}