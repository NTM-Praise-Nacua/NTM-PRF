<?php

namespace App\Http\Controllers;

use App\Services\RequisitionService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected $getStatusCounters;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(RequisitionService $service)
    {
        $this->middleware('auth');
        $this->getStatusCounters = $service;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $counters = $this->getStatusCounters->getStatusCounters();
        
        return view('dashboard', compact('counters'));
    }
}
