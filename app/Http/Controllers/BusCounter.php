<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
class BusCounter extends Controller
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
            $buscounter = \App\BusCounter::where('operatorid', user()->id)->orderBy('id', 'DESC')->get();
        } else {
            $buscounter = \App\BusCounter::orderBy('id', 'DESC')->get();
        }

        return view('system.buscounter.index', ['buscounter' => $buscounter]);
    }

    public function add()
    {
        $busmanagers = User::where('roleid',roleid_by_name('busmanager'))->orderBy('company', 'ASC')->get();

        return view('system.buscounter.add',['busmanager'=> $busmanagers]);
    }

    public function addpost(Request $request)
    {
        $fields = $request->validate([
            'operator' => 'required',
            'name' => 'required',
            'location' => 'required'
        ]);

        $buscounter = \App\BusCounter::create([
            'name' => $fields['name'],
            'operatorid' => $fields['operator'],
            'location' => $fields['location'],
        ]);

        if($buscounter->id)
        {
            $request->session()->flash('status_success', 'Bus Counter' .$buscounter->name.' Added successfully');
            return redirect()->route('buscounter');
        }

        $request->session()->flash('status_error', 'Something went wrong. Please try again');
        return redirect()->back();
    }

    public function edit($id, Request $request)
    {
        $buscounter = \App\BusCounter::find($id);
        $busmanagers = User::where('roleid',roleid_by_name('busmanager'))->orderBy('company', 'ASC')->get();

        if($buscounter!=null) {
            return view('system.buscounter.edit', ['buscounter' => $buscounter],['busmanager'=> $busmanagers]);
        }

        $request->session()->flash('status_error', 'buscounter not found');
        return redirect()->back();
    }

    public function editpost($id, Request $request)
    {
        $buscounter = \App\BusCounter::find($id);
        

        if($buscounter!=null) {
            $fields = $request->validate([
                'name'          => 'required',
                'location'      => 'required',
                'operator'    => 'required',
            ]);

            if(!empty($fields['operator']))

            $buscounter->operatorid = $fields['operator'];
            $buscounter->name = $fields['name'];
            $buscounter->location = $fields['location'];
           
            if($buscounter->save()) {
                $request->session()->flash('status_success', 'Bus Counter '.$buscounter->name.' modified successfully');
                return redirect()->route('buscounter');
            }

            $request->session()->flash('status_error', 'Something went wrong. Please try again');
            return redirect()->back();
        }

        $request->session()->flash('status_error', 'User not found');
        return redirect()->back();

    }

    public function delete($id, Request $request)
    {
        $buscounter = \App\BusCounter::find($id);

        if($buscounter!=null) {
            if($buscounter->delete()) {
                $request->session()->flash('status_success', 'Bus Counter '.$buscounter->name.' removed successfully');
                return redirect()->route('buscounter');
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
                $buscounter = \App\BusCounter::where('operatorid', user()->id)->where(function ($q)use ($request) {
                    $q->whereHas('operator', function ($query) use ($request) {
                        $query->where('company', 'like', '%'.$request->search.'%');
                    })->orWhere('name', 'like', '%'.$request->search.'%')->orWhere('location', 'like', '%'.$request->search.'%');
                })->orderBy('id', 'ASC');
            } else {
                $buscounter = \App\BusCounter::whereHas('operator', function ($query) use ($request) {
                    $query->where('company', 'like', '%'.$request->search.'%');
                })->orWhere('name', 'like', '%'.$request->search.'%')->orWhere('location', 'like', '%'.$request->search.'%')->orderBy('id', 'ASC');
            }

        } else {
            if(user()->roleid == roleid_by_name('busmanager')) {
                $buscounter = \App\BusCounter::where('operatorid', user()->id)->orderBy('id', 'ASC');
            } else {
                $buscounter = \App\BusCounter::orderBy('id', 'ASC');
            }
        }

        $buscounters = $buscounter->get();
        $response = [];

        if(!empty($buscounters))
        {
            foreach ($buscounters as $buscounter){
                $response[] = [
                    $buscounter->operator->company,
                    $buscounter->name,
                    $buscounter->location,
                    ' <a class="btn btn-primary btn-sm" href="'.route('buscounteredit', ['id' => $buscounter->id]).'">Update</a>'.
                    ' <a class="btn btn-danger btn-sm" href="'.route('buscounterdelete', ['id' => $buscounter->id]).'">Remove</a>'
                ];
                        
            }
        }

        return response()->json($response);
    }

    public function ajaxbyoperator(Request $request)
    {
        $response = [];

        if($request->has('operator') && !empty($request->operator))
        {
            $buscounters = \App\BusCounter::where('operatorid', $request->operator)->orderBy('location', 'ASC')->orderBy('name', 'ASC')->get();

            if(!empty($buscounters))
            {
                foreach ($buscounters as $buscounter){
                    $response[] = [
                        'value' => $buscounter->id,
                        'text' => $buscounter->name . ' ['.$buscounter->location.']',
                    ];
                }
            }
        }

        return response()->json($response);
    }
}
