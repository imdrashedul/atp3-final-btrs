<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
class Profile extends Controller
{
   
    public function index(Request $req)
    {
        return view('system.profile.index');
    }

    public function update(Request $request)
    {
        $user = user();
        

        if($user!=null) {
            $rules = [
                'name'       => 'required',
                'email'      => 'required|unique:users,email,'.$user->id,
                'password'   => 'nullable',
            ];
            if($user->roleid == roleid_by_name('busmanager')){
                $rules['company'] = 'required';
            }
            
            $fields = $request->validate($rules);

            $user->name = $fields['name'];
            $user->email = $fields['email'];
            if(!empty($fields['password'])){
                $user->password = $fields['password'];
            }
            if($user->roleid == roleid_by_name('busmanager')){
                $user->company = $fields['company'];
            }
            
            
           
            if($user->save()) {
                $request->session()->flash('status_success', 'Profile modified successfully');
                return redirect()->route('profile');
            }

            $request->session()->flash('status_error', 'Something went wrong. Please try again');
            return redirect()->back();
        }

        $request->session()->flash('status_error', 'User not found');
        return redirect()->back();

    }

}
