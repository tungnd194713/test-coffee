<?php

namespace App\Http\Controllers;

use App\Models\Timesheet;
use Carbon\Carbon;
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
        $user = Auth::user();
        $timesheet = Timesheet::where('user_id', $user->id)->where('date', Carbon::now()->toDateString())->first();
        $checkData = [
            'check_in' => $timesheet?->check_in,
            'check_out' => $timesheet?->check_out,
        ];
        return view('home', compact('checkData'));
    }
}
