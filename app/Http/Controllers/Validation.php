<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class Validation extends Controller
{
    public function __construct()
    {
        $this->middleware([
            'access.role:super,admin,supportstaff',
            'access.feature:verifybusmanager'
        ]);
    }

    public function index()
    {
        $users = User::where('validated', 0)->get();
        return view('system.validation.index', ['users' => $users]);
    }

    public function validated($id, Request $request)
    {
        $user = User::where('id', $id)->where('validated', 0)->first();

        if($user!=null)
        {
            $user->validated = 1;

            if($user->save())
            {
                $request->session()->flash('status_success', 'User '.$user->name.' validated successfully');
                return redirect()->back();
            }

            $request->session()->flash('status_error', 'Something went wrong. Please try again');
            return redirect()->back();
        }

        $request->session()->flash('status_error', 'User Not Found or Already Validated');
        return redirect()->back();
    }

    public function delete($id, Request $request)
    {
        $user = User::where('id', $id)->where('validated', 0)->first();

        if($user!=null)
        {
            if($user->delete())
            {
                $request->session()->flash('status_success', 'User '.$user->name.' remove successfully');
                return redirect()->back();
            }

            $request->session()->flash('status_error', 'Something went wrong. Please try again');
            return redirect()->back();
        }

        $request->session()->flash('status_error', 'User Not Found or Already Validated');
        return redirect()->back();
    }

}
