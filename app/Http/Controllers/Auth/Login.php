<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class Login extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function verify(Request $request)
    {
        $filtered = $request->validate([
            'email' => 'bail|required|email|exists:users,email',
            'password' => 'required'
        ]);

        $user = User::where('email', $filtered['email'])->first();

        if($user && $user->password == $filtered['password']) {

            $request->session()->put('user', $user);

            return redirect()->route('system');
        }

        return back()->withErrors(['password' => 'Password did not match'])->withInput();
    }
}
