<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class System extends Controller
{
    public function index()
    {
        return view('system.dashboard');
    }
}
