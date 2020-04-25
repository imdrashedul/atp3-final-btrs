<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Role;
use App\RolePermission;
use App\UserPermission;
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
        $user->roleid       = Role::where('name', 'busmanager')->first()->id;
        $user->validated  = "0";
        $user->registered = date('Y-m-d H:i:s');


        if($user->save())
        {
           $permissions = array_map(function ($permission) use ( $user) {
                return [
                    'userid' => $user->id,
                    'permissionid' => $permission->id
                ];
           }, $user->role->permissions->all());

           if(!empty($permissions)) UserPermission::insert( $permissions );

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
