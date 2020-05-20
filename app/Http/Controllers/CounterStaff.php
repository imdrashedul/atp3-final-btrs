<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\App;

class CounterStaff extends Controller
{
    public function __construct()
    {
        $this->middleware([
            'access.role:super,admin,busmanager,supportstaff'
        ]);
    }

    public function index()
    {
        if(user()->roleid == roleid_by_name('busmanager')) {
            $users = User::where('operatorid', user()->id)->where('validated', 1)->where('roleid', roleid_by_name('counterstaff'))->orderBy('id', 'DESC')->get();
        } else {
            $users = User::where('validated', 1)->where('roleid', roleid_by_name('counterstaff'))->orderBy('id', 'DESC')->get();
        }
        return view('system.counterstaff.index', ['users' => $users]);
    }

    public function add()
    {
        $busmanagers = User::where('roleid',roleid_by_name('busmanager'))->orderBy('company', 'ASC')->get();
        if(user()->roleid == roleid_by_name('busmanager')) {
            $counters = \App\BusCounter::where('operatorid', user()->id)->orderBy('location', 'ASC')->orderBy('name', 'ASC')->get();
        } else {
            $counters = [];
        }

        return view('system.counterstaff.add',['busmanager'=> $busmanagers, 'counters' => $counters]);
    }

    public function addpost(Request $request)
    {
        $fields = $request->validate([
            'operator' => 'required',
            'counter' => 'required',
            'name' => 'required',
            'email' => 'bail|required|email|unique:users,email',
            'password' => 'required'
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => $fields['password'],
            'operatorid' => $fields['operator'],
            'counterid' => $fields['counter'],
            'roleid' => roleid_by_name('counterstaff'),
            'validated' => 1,
            'registered' => date('Y-m-d H:i:s')
        ]);

        if($user->id)
        {
            attach_role_permissions($user);
            $request->session()->flash('status_success', 'Counter Staff ' .$user->name.' Added successfully');
            return redirect()->route('counterstaff');
        }

        $request->session()->flash('status_error', 'Something went wrong. Please try again');
        return redirect()->back();
    }

    public function edit($id, Request $request)
    {
        $counterstaff = User::find($id);
        $busmanagers = User::where('roleid',roleid_by_name('busmanager'))->orderBy('company', 'ASC')->get();
        if(user()->roleid == roleid_by_name('busmanager')) {
            $counters = \App\BusCounter::where('operatorid', user()->id)->orderBy('location', 'ASC')->orderBy('name', 'ASC')->get();
        } else {
            $counters = [];
        }

        if($counterstaff!=null) {
            return view('system.counterstaff.edit', ['counterstaff' => $counterstaff, 'busmanager'=> $busmanagers, 'counters' => $counters]);
        }

        $request->session()->flash('status_error', 'Counter Staff not found');
        return redirect()->back();
    }

    public function editpost($id, Request $request)
    {
        $counterstaff = User::find($id);

        if($counterstaff!=null) {

            $fields = $request->validate([
                'operator' => 'required',
                'counter' => 'required',
                'name' => 'required',
                'email' => 'bail|required|email|unique:users,email,'.$counterstaff->id,
                'password' => 'nullable'
            ]);

            $counterstaff->operatorid = $fields['operator'];
            $counterstaff->counterid = $fields['counter'];
            $counterstaff->email = $fields['email'];
            $counterstaff->name = $fields['name'];

            if(!empty($fields['password'])) {
                $counterstaff->password = $fields['password'];
            }

            if($counterstaff->save()) {
                $request->session()->flash('status_success', 'Counter Staff '.$counterstaff->name.' modified successfully');
                return redirect()->route('counterstaff');
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
                $request->session()->flash('status_success', 'Counter Staff '.$user->name.' removed successfully');
                return redirect()->route('counterstaff');
            }

            $request->session()->flash('status_error', 'Something went wrong. Please try again');
            return redirect()->back();
        }

        $request->session()->flash('status_error', 'Bus Counter not found');
        return redirect()->back();
    }


    public function ajaxsearch(Request $request)
    {
        if($request->has('search') && !empty($request->search))
        {
            if(user()->roleid == roleid_by_name('busmanager')) {
                $counterstaffs = User::where('operatorid', user()->id)->where(function($q) use ($request) {
                    $q->whereHas('role', function ($query) {
                        $query->where('name', 'counterstaff');
                    })->whereHas('operator', function ($query) use ($request) {
                        $query->where('company', 'like', '%' . $request->search . '%');
                    })->orWhereHas('counter', function ($query) use ($request) {
                        $query->where('name', 'like', '%' . $request->search . '%')->orWhere('location', 'like', '%' . $request->search . '%');
                    })->orWhere('name', 'like', '%' . $request->search . '%')->orWhere('email', 'like', '%' . $request->search . '%');
                })->orderBy('id', 'DESC');
            } else {
                $counterstaffs = User::whereHas('role', function ($query) {
                    $query->where('name', 'counterstaff');
                })->whereHas('operator', function ($query) use ($request) {
                    $query->where('company', 'like', '%' . $request->search . '%');
                })->orWhereHas('counter', function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->search . '%')->orWhere('location', 'like', '%' . $request->search . '%');
                })->orWhere('name', 'like', '%' . $request->search . '%')->orWhere('email', 'like', '%' . $request->search . '%')->orderBy('id', 'DESC');
            }
        } else {
            if(user()->roleid == roleid_by_name('busmanager')) {
                $counterstaffs = User::where('operatorid', user()->id)->whereHas('role', function ($query) {
                    $query->where('name', 'counterstaff');
                })->orderBy('id', 'DESC');
            } else {
                $counterstaffs = User::whereHas('role', function ($query) {
                    $query->where('name', 'counterstaff');
                })->orderBy('id', 'DESC');
            }
        }

        $counterstaffs = $counterstaffs->get();
        $response = [];

        if(!empty($counterstaffs))
        {
            foreach ($counterstaffs as $counterstaff){
                $row = [];
                $row[] = $counterstaff->name;
                $row[] = $counterstaff->email;
                $row[] = $counterstaff->counter->name . '['.$counterstaff->counter->location.']';
                if(user()->role->name!='busmanager') $row[] = $counterstaff->operator->company;
                $row[] = date_format(date_create($counterstaff->registered),"j M Y g:i a");
                $actions = [ 'text' => '', 'css' => [
                    'text-align' => 'center'
                ]];

                if(user_has_access(['managecounterstaffpermission']))
                    $actions['text'] .= '<a class="btn btn-secondary btn-sm" href="'.route('managerole_permissionuser', ['id' => $counterstaff->id]).'">Permissions</a>';
                if(user_has_access(['editcounterstaff']))
                    $actions['text'] .= ' <a class="btn btn-primary btn-sm" href="'.route('counterstaffedit', ['id' => $counterstaff->id]).'">Update</a>';
                if(user_has_access(['removebus']))
                    $actions['text'] .= ' <a class="btn btn-danger btn-sm" href="'.route('counterstaffdelete', ['id' => $counterstaff->id]).'">Remove</a>';
                if(!empty($actions))
                    $row[] = $actions;

                $response[] = $row;
            }
        }

        return response()->json($response);
    }
}

