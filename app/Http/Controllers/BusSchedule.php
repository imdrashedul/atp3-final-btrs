<?php

namespace App\Http\Controllers;

use App\Bus;
use App\User;
use App\BusCounter;
use Illuminate\Http\Request;

class BusSchedule extends Controller
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
            $busschedules = \App\BusSchedule::whereHas('bus', function($query) {
                $query->where('operatorid', user()->id);
            })->orderBy('id', 'DESC')->get();
        } else {
            $busschedules = \App\BusSchedule::orderBy('id', 'DESC')->get();
        }

        return view('system.busschedule.index', ['busschedules' => $busschedules]);
    }

    public function add()
    {
        $busmanagers = User::where('roleid',roleid_by_name('busmanager'))->orderBy('company', 'ASC')->get();
        if(user()->roleid == roleid_by_name('busmanager')) {
            $counters = BusCounter::where('operatorid', user()->id)->orderBy('location', 'ASC')->orderBy('name', 'ASC')->get();
            $buses = Bus::where('operatorid', user()->id)->orderBy('registration', 'ASC')->orderBy('name', 'ASC')->get();
        } else {
            $counters = [];
            $buses = [];
        }

        return view('system.busschedule.add',['busmanager'=> $busmanagers, 'counters' => $counters, 'buses'=>$buses]);
    }

    public function addpost(Request $request)
    {
        $fields = $request->validate([
            'bus'          => 'required',
            'boarding'          => 'required',
            'from'          => 'required',
            'to'          => 'required',
            'departure'  => 'required',
            'arrival'  => 'required',
            'fare'      => 'required|numeric',
        ]);

        $bus = \App\BusSchedule::create([
            'busid' => $fields['bus'],
            'boardingid' => $fields['boarding'],
            'route' => $fields['from'].'-'.$fields['to'],
            'departure' => $fields['departure'],
            'arrival' => $fields['arrival'],
            'fare' => $fields['fare']
        ]);

        if($bus->id)
        {
            $request->session()->flash('status_success', 'Bus Schedule Added successfully');
            return redirect()->route('busschedule');
        }

        $request->session()->flash('status_error', 'Something went wrong. Please try again');
        return redirect()->back();
    }

    public function edit($id, Request $request)
    {
        $busschedule = \App\BusSchedule::find($id);
        $busmanagers = User::where('roleid',roleid_by_name('busmanager'))->orderBy('company', 'ASC')->get();
        if(user()->roleid == roleid_by_name('busmanager')) {
            $counters = BusCounter::where('operatorid', user()->id)->orderBy('location', 'ASC')->orderBy('name', 'ASC')->get();
            $buses = Bus::where('operatorid', user()->id)->orderBy('registration', 'ASC')->orderBy('name', 'ASC')->get();
        } else {
            $counters = [];
            $buses = [];
        }

        if($busschedule!=null) {
            return view('system.busschedule.edit', ['busschedule' => $busschedule, 'busmanager'=> $busmanagers, 'counters' => $counters, 'buses'=>$buses]);
        }

        $request->session()->flash('status_error', 'Bus Schedule not found');
        return redirect()->back();
    }

    public function editpost($id, Request $request)
    {
        $bus = \App\BusSchedule::find($id);


        if($bus!=null) {
            $fields = $request->validate([
                'bus'          => 'required',
                'boarding'          => 'required',
                'from'          => 'required',
                'to'          => 'required',
                'departure'  => 'required',
                'arrival'  => 'required',
                'fare'      => 'required|numeric',
            ]);

            $bus->busid = $fields['bus'];
            $bus->route = $fields['from'].'-'.$fields['to'];
            $bus->boardingid = $fields['boarding'];
            $bus->departure = $fields['departure'];
            $bus->arrival = $fields['arrival'];
            $bus->fare = $fields['fare'];

            if($bus->save()) {
                $request->session()->flash('status_success', 'Bus Schedule modified successfully');
                return redirect()->route('busschedule');
            }

            $request->session()->flash('status_error', 'Something went wrong. Please try again');
            return redirect()->back();
        }

        $request->session()->flash('status_error', 'Bus Schedule not found');
        return redirect()->back();

    }

    public function delete($id, Request $request)
    {
        $bus = \App\BusSchedule::find($id);

        if($bus!=null) {
            if($bus->delete()) {
                $request->session()->flash('status_success', 'Bus Schedule removed successfully');
                return redirect()->route('busschedule');
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
                $busschedules = \App\BusSchedule::whereHas('bus', function ($query) use ($request) {
                    $query->where('operatorid', user()->id)->where(function ($q) use ($request) {
                        $q->whereHas('operator', function ($_q) use ($request) {
                            $_q->where('company', 'like', '%'.$request->search.'%');
                        })->orWhere('name', 'like', '%'.$request->search.'%')->orWhere('registration', $request->search);
                    });
                })->orWhereHas('boarding', function ($query) use ($request) {
                    $query->where('name', 'like', '%'.$request->search.'%')->orWhere('location', 'like', '%'.$request->search.'%');
                })->orWhere('LOWER(route)', 'like', '%'.strtolower($request->search).'%')->orderBy('id', 'DESC');
            } else {
                $busschedules = \App\BusSchedule::whereHas('bus', function ($query) use ($request) {
                    $query->where(function ($q) use ($request) {
                        $q->whereHas('operator', function ($_q) use ($request) {
                            $_q->where('company', 'like', '%'.$request->search.'%');
                        })->orWhere('name', 'like', '%'.$request->search.'%')->orWhere('registration', $request->search);
                    });
                })->orWhereHas('boarding', function ($query) use ($request) {
                    $query->where('name', 'like', '%'.$request->search.'%')->orWhere('location', 'like', '%'.$request->search.'%');
                })->orWhere('LOWER(route)', 'like', '%'.strtolower($request->search).'%')->orderBy('id', 'DESC');
            }

        } else {
            if(user()->role->name == 'busmanager') {
                $busschedules = \App\BusSchedule::whereHas('bus', function ($query) use ($request) {
                    $query->where('operatorid', user()->id);
                })->orderBy('id', 'DESC');
            } else {
                $busschedules = \App\BusSchedule::orderBy('id', 'DESC');
            }
        }

        $busschedules = $busschedules->get();
        $response = [];

        if(!empty($busschedules))
        {
            foreach ($busschedules as $busschedule){
                $row = [];
                if(user()->role->name!='busmanager') $row[] = $busschedule->bus->operator->company;
                $row[] = $busschedule->bus->name . ' ['.$busschedule->bus->registration.']';
                $row[] = $busschedule->route;
                $row[] = date_format(date_create($busschedule->departure),"j M Y g:i a");
                $row[] = date_format(date_create($busschedule->arrival),"j M Y g:i a");
                $row[] = [
                    'text' => $busschedule->fare.' BDT',
                    'css' => [ 'text-align' => 'right' ]
                ];
                $row[] = $busschedule->boarding->name;
                $actions = [ 'text' => '', 'css' => [
                    'text-align' => 'center'
                ]];

                if(user_has_access(['editbusschedule']))
                    $actions['text'] .= ' <a class="btn btn-primary btn-sm" href="'.route('busscheduleedit', ['id' => $busschedule->id]).'">Update</a>';
                if(user_has_access(['removebusschedule']))
                    $actions['text'] .= ' <a class="btn btn-danger btn-sm" href="'.route('busscheduledelete', ['id' => $busschedule->id]).'">Remove</a>';
                if(!empty($actions))
                    $row[] = $actions;

                $response[] = $row;
            }
        }

        return response()->json($response);
    }
}
