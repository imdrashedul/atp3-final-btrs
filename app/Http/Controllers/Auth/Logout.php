<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Logout extends Controller
{
    public function index(Request $request)
    {
        $request->session()->forget('user');
        $request->session()->flush();
        return redirect()->route('login');
    }
}
