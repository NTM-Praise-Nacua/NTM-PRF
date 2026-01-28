<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use App\Services\RequisitionService;
use Illuminate\Http\Request;
use Carbon\Carbon;

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
    public function index(Request $request)
    {
        $monthYear = $request->input('month_year', Carbon::now()->format('m-Y'));
        $counters = $this->getStatusCounters->getStatusCounters($monthYear);
        $formatMonthYear = Carbon::createFromFormat('m-Y', $monthYear);
        $formatted = $formatMonthYear->format('M. Y');
        
        $isApprover = User::where('approver_id', auth()->user()->id)->exists() || Department::where('approver', auth()->user()->id)->exists();
        
        return view('dashboard', compact('counters', 'formatted', 'isApprover'));
    }
}
