<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;

class Register extends Controller
{
    public function index()
    {
        return view('auth.register');
    }
    public function create(Request $request)
    {
        $request->validate([
            'email'      => 'bail|required|email|unique:users,email',
            'password'   => 'required|confirmed',
            'name'       => 'required',
            'company'    => 'bail|required|unique:users,company'
        ]);

        
        $user = new User();
        $user->name       = $request->name;
        $user->company    = $request->company;  
        $user->email      = $request->email;
        $user->password   = $request->password;
        $user->roleid       = roleid_by_name('busmanager');
        $user->validated  = "0";
        $user->registered = date('Y-m-d H:i:s');


        if($user->save())
        {
            attach_role_permissions($user);
            $request->session()->flash('register', $request->name.' You have join successfully');
            $request->session()->flash('register_email', $request->email);
            return redirect()->route('login');
        }
        else
        {
            return redirect()->route('register');

        }
    }


}
