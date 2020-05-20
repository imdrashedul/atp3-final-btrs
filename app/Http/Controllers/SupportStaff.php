<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class SupportStaff extends Controller
{
    public function __construct()
    {
        $this->middleware([
           'access.role:super,admin'
        ]);
    }

    public function index()
    {
        $users = User::where('validated', 1)->where('roleid', roleid_by_name('supportstaff'))->orderBy('id', 'DESC')->get();
        return view('system.supportstaff.index', ['users' => $users]);
    }

    public function add()
    {
        return view('system.supportstaff.add');
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
            'roleid' => roleid_by_name('supportstaff'),
            'validated' => 1,
            'registered' => date('Y-m-d H:i:s')
        ]);

        if($user->id)
        {
            attach_role_permissions($user);
            $request->session()->flash('status_success', 'Support Staff '.$user->name.' Added successfully');
            return redirect()->route('supportstaff');
        }

        $request->session()->flash('status_error', 'Something went wrong. Please try again');
        return redirect()->back();
    }

    public function edit($id, Request $request)
    {
        $user = User::find($id);

        if($user!=null) {
            return view('system.supportstaff.edit', ['user' => $user]);
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
                return redirect()->route('supportstaff');
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
                return redirect()->route('supportstaff');
            }

            $request->session()->flash('status_error', 'Something went wrong. Please try again');
            return redirect()->back();
        }

        $request->session()->flash('status_error', 'User not found');
        return redirect()->back();
    }

    public function ajaxsearch(Request $request)
    {
        $users = User::where('roleid', roleid_by_name('supportstaff'));

        if($request->has('search') && !empty($request->search))
        {
            $users->where(function ($query) use ($request) {
                $query->where('name', 'like', '%'.$request->search.'%')->orWhere('email', $request->search);
            });
        }

        $users = $users->orderBy('id', 'DESC')->get();
        $response = [];

        if(!empty($users))
        {
            foreach ($users as $user) {
                $row = [];
                $row[] = $user->name;
                $row[] = $user->email;
                $row[] = date_format(date_create($user->registered),"j M Y g:i a");
                $actions = [ 'text' => '', 'css' => [
                    'text-align' => 'center'
                ]];

                if(user_has_access(['managesupportstaffpermission']))
                    $actions['text'] .= '<a class="btn btn-secondary btn-sm" href="'.route('managerole_permissionuser', ['id' => $user->id]).'">Permissions</a>';
                if(user_has_access(['editsupportstaff']))
                    $actions['text'] .= ' <a class="btn btn-primary btn-sm" href="'.route('supportstaffedit', ['id' => $user->id]).'">Update</a>';
                if(user_has_access(['removesupportstaff']))
                    $actions['text'] .= ' <a class="btn btn-danger btn-sm" href="'.route('supportstaffdelete', ['id' => $user->id]).'">Remove</a>';
                if(!empty($actions))
                    $row[] = $actions;

                $response[] = $row;
            }
        }

        return response()->json($response);
    }

}
