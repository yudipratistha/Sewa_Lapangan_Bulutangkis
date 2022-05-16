<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (auth()->user()->user_status == 2) {
            return redirect()->route('pemilikLapangan.dashboard');
        }else{
            return redirect()->route('home');
        }
        
    }

    public function pemilikLapanganHome()
    {
        return view('pemilik_lapangan.pemilikLapanganDashboard');

    }
}
