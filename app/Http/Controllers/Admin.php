<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class Admin extends Controller
{
    public function __construct()
    {
        $this->middleware([
            'access.role:super',
        ]);
    }

    public function index()
    {
        $users = User::where('validated', 1)->where('roleid', roleid_by_name('admin'))->orderBy('id', 'DESC')->get();
        return view('system.admin.index', ['users' => $users]);
    }

    public function add()
    {
        return view('system.admin.add');
    }

    public function addpost(Request $request)
    {
        $fields = $request->validate([
            'email'      => 'bail|required|email|unique:users,email',
            'password'   => 'required|confirmed',
            'name'       => 'required',
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => $fields['password'],
            'roleid' => roleid_by_name('admin'),
            'validated' => 1,
            'registered' => date('Y-m-d H:i:s')
        ]);

        if($user->id)
        {
            attach_role_permissions($user);
            $request->session()->flash('status_success', 'Admin '.$user->name.' Added successfully');
            return redirect()->route('admin');
        }

        $request->session()->flash('status_error', 'Something went wrong. Please try again');
        return redirect()->back();
    }

    public function edit($id, Request $request)
    {
        $user = User::find($id);

        if($user!=null) {
           return view('system.admin.edit', ['user' => $user]);
        }

        $request->session()->flash('status_error', 'User not found');
        return redirect()->back();
    }

    public function editpost($id, Request $request)
    {
        $user = User::find($id);

        if($user!=null) {
            $fields = $request->validate([
                'email'      => 'bail|required|email|unique:users,email,'.$id,
                'name'       => 'required',
            ]);

            if(!empty($fields['password'])) $user->password = $fields['password'];

            $user->name = $fields['name'];
            $user->email = $fields['email'];

            if($user->save()) {
                $request->session()->flash('status_success', 'User '.$user->name.' modified successfully');
                return redirect()->route('admin');
            }

            $request->session()->flash('status_error', 'Something went wrong. Please try again');
            return redirect()->back();
        }

        $request->session()->flash('status_error', 'User not found');
        return redirect()->back();

    }

    public function delete($id, Request $request)
    {
        $user = User::find($id);

        if($user!=null) {
            if($user->delete()) {
                $request->session()->flash('status_success', 'User '.$user->name.' removed successfully');
                return redirect()->route('admin');
            }

            $request->session()->flash('status_error', 'Something went wrong. Please try again');
            return redirect()->back();
        }

        $request->session()->flash('status_error', 'User not found');
        return redirect()->back();
    }

}
