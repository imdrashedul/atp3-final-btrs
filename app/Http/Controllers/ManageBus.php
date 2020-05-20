<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Bus;

class ManageBus extends Controller
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
            $buses = Bus::where('operatorid', user()->id)->orderBy('id', 'DESC')->get();
        } else {
            $buses = Bus::orderBy('id', 'DESC')->get();
        }

        return view('system.buses.index', ['buses' => $buses]);
    }

    public function add()
    {
        $busmanagers = User::where('roleid',roleid_by_name('busmanager'))->orderBy('company', 'ASC')->get();
        return view('system.buses.add',['busmanager'=> $busmanagers]);
    }

    public function addpost(Request $request)
    {
        $fields = $request->validate([
            'operator'      => 'required',
            'name'          => 'required',
            'registration'  => 'required|unique:buses,registration',
            'seatsrow'      => 'required|numeric',
            'seatscolumn'      => 'required|numeric'
        ]);

        $bus = Bus::create([
            'name' => $fields['name'],
            'operatorid' => $fields['operator'],
            'registration' => $fields['registration'],
            'seats_row' => $fields['seatsrow'],
            'seats_column' => $fields['seatscolumn'],
        ]);

        if($bus->id)
        {
            $request->session()->flash('status_success', 'Bus Counter' .$bus->name.' ['.$bus->registration.'] Added successfully');
            return redirect()->route('buses');
        }

        $request->session()->flash('status_error', 'Something went wrong. Please try again');
        return redirect()->back();
    }

    public function edit($id, Request $request)
    {
        $bus = Bus::find($id);
        $busmanagers = User::where('roleid',roleid_by_name('busmanager'))->orderBy('company', 'ASC')->get();

        if($bus!=null) {
            return view('system.buses.edit', ['bus' => $bus],['busmanager'=> $busmanagers]);
        }

        $request->session()->flash('status_error', 'buscounter not found');
        return redirect()->back();
    }

    public function editpost($id, Request $request)
    {
        $bus = Bus::find($id);


        if($bus!=null) {
            $fields = $request->validate([
                'operator'      => 'required',
                'name'          => 'required',
                'registration'  => 'required|unique:buses,registration,'.$id,
                'seatsrow'      => 'required|numeric',
                'seatscolumn'      => 'required|numeric'
            ]);

            $bus->operatorid = $fields['operator'];
            $bus->name = $fields['name'];
            $bus->registration = $fields['registration'];
            $bus->seats_row = $fields['seatsrow'];
            $bus->seats_column = $fields['seatscolumn'];

            if($bus->save()) {
                $request->session()->flash('status_success', 'Bus '.$bus->name.' ['.$bus->registration.'] modified successfully');
                return redirect()->route('buses');
            }

            $request->session()->flash('status_error', 'Something went wrong. Please try again');
            return redirect()->back();
        }

        $request->session()->flash('status_error', 'User not found');
        return redirect()->back();

    }

    public function delete($id, Request $request)
    {
        $bus = Bus::find($id);

        if($bus!=null) {
            if($bus->delete()) {
                $request->session()->flash('status_success', 'Bus '.$bus->name.' ['.$bus->registration.'] removed successfully');
                return redirect()->route('buses');
            }

            $request->session()->flash('status_error', 'Something went wrong. Please try again');
            return redirect()->back();
        }

        $request->session()->flash('status_error', 'Bus not found');
        return redirect()->back();
    }

    public function ajaxsearch(Request $request)
    {
        if($request->has('search') && !empty($request->search))
        {
            if(user()->role->name == 'busmanager') {
                $buses = Bus::where('operatorid', user()->id)->where(function ($q)use ($request) {
                    $q->whereHas('operator', function ($query) use ($request) {
                        $query->where('company', 'like', '%'.$request->search.'%');
                    })->orWhere('name', 'like', '%'.$request->search.'%')->orWhere('registration', $request->search);
                })->orderBy('id', 'DESC');
            } else {
                $buses = Bus::whereHas('operator', function ($query) use ($request) {
                    $query->where('company', 'like', '%'.$request->search.'%');
                })->orWhere('name', 'like', '%'.$request->search.'%')->orWhere('registration', $request->search)->orderBy('id', 'DESC');
            }

        } else {
            if(user()->role->name == 'busmanager') {
                $buses = Bus::where('operatorid', user()->id)->orderBy('id', 'DESC');
            } else {
                $buses = Bus::orderBy('id', 'DESC');
            }
        }

        $buses = $buses->get();
        $response = [];

        if(!empty($buses))
        {
            foreach ($buses as $bus){
                $row = [];
                if(user()->role->name!='busmanager') $row[] = $bus->operator->company;
                $row[] = $bus->name;
                $row[] = $bus->registration;
                $row[] = $bus->seats_row*$bus->seats_column;
                $actions = [ 'text' => '', 'css' => [
                    'text-align' => 'center'
                ]];

                if(user_has_access(['editbus']))
                    $actions['text'] .= ' <a class="btn btn-primary btn-sm" href="'.route('busesedit', ['id' => $bus->id]).'">Update</a>';
                if(user_has_access(['removebus']))
                    $actions['text'] .= ' <a class="btn btn-danger btn-sm" href="'.route('busesdelete', ['id' => $bus->id]).'">Remove</a>';
                if(!empty($actions))
                    $row[] = $actions;

                $response[] = $row;
            }
        }

        return response()->json($response);
    }

    public function ajaxbyoperator(Request $request)
    {
        $response = [];

        if($request->has('operator') && !empty($request->operator))
        {
            $buses = Bus::where('operatorid', $request->operator)->orderBy('name', 'ASC')->get();

            if(!empty($buses))
            {
                foreach ($buses as $bus){
                    $response[] = [
                        'value' => $bus->id,
                        'text' => $bus->name . ' ['.$bus->registration.']',
                    ];
                }
            }
        }

        return response()->json($response);
    }
}
