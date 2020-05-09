<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
class BusManager extends Controller
{
    public function __construct()
    {
        $this->middleware([
           'access.role:super,admin,supportstaff'
        ]);
    }

    public function index()
    {
        $users = User::where('validated', 1)->where('roleid', roleid_by_name('busmanager'))->orderBy('id', 'DESC')->get();
        return view('system.busmanager.index', ['users' => $users]);
    }

    public function edit($id, Request $request)
    {
        $user = User::find($id);

        if($user!=null) {
            return view('system.busmanager.edit', ['user' => $user]);
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
                'company'    => 'required',

            ]);

            if(!empty($fields['password'])) $user->password = $fields['password'];

            $user->name = $fields['name'];
            $user->email = $fields['email'];
            $user->company = $fields['company'];

            if($user->save()) {
                $request->session()->flash('status_success', 'User '.$user->name.' modified successfully');
                return redirect()->route('busmanager');
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
                return redirect()->route('busmanager');
            }

            $request->session()->flash('status_error', 'Something went wrong. Please try again');
            return redirect()->back();
        }

        $request->session()->flash('status_error', 'User not found');
        return redirect()->back();
    }

    public function ajaxsearch(Request $request)
    {
        $users = User::where('roleid', roleid_by_name('busmanager'));

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
                $response[] = [
                    $user->name,
                    $user->email,
                    date_format(date_create($user->registered),"j M Y g:i a"),
                    $user->company,
                    (user_has_role(['admin', 'super', 'supportstaff']) && user_has_access(['managebusmanagerpermission']) ? '<a class="btn btn-secondary btn-sm" href="'.route('managerole_permissionuser', ['id' => $user->id]).'">Permissions</a>' : '').
                    ' <a class="btn btn-primary btn-sm" href="'.route('busmanageredit', ['id' => $user->id]).'">Update</a>'.
                    ' <a class="btn btn-danger btn-sm" href="'.route('busmanagerdelete', ['id' => $user->id]).'">Remove</a>'
                ];
            }
        }

        return response()->json($response);
    }
}
