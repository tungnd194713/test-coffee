<?php

namespace App\Http\Controllers;

use App\Models\Timesheet;
use App\Http\Requests\StoreTimesheetRequest;
use App\Http\Requests\UpdateTimesheetRequest;
use App\Services\TimesheetServiceInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TimesheetController extends Controller
{

    public function __construct(protected TimesheetServiceInterface $timesheetService)
    {
        
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $timesheets = $this->timesheetService->getListTimesheet(Auth::user());

        // return view('list', compact('timesheets'));
        return response()->json($timesheets);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreTimesheetRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTimesheetRequest $request)
    {
        $params = $request->only('tasks', 'difficulties', 'todo', 'date');

        $this->timesheetService->storeTimesheet(Auth::user(), $params);
        return $this->successResponse('Timesheets created', 'Success');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Timesheet  $timesheet
     * @return \Illuminate\Http\Response
     */
    public function show(Timesheet $timesheet)
    {
        if (!Auth::user()->can('view', $timesheet)) {
            abort(403);
        }

        return view('timesheet', compact('timesheet'));
    }

    public function showByDate(Request $request) {
        $params = $request->only('date');
        $timesheet = $this->timesheetService->showByDate(Auth::user(), $params['date']);
        if (!$timesheet) {
            return $this->successResponse([], 'Not found');
        }
        return $this->successResponse($timesheet->toArray(), 'Success');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Timesheet  $timesheet
     * @return \Illuminate\Http\Response
     */
    public function edit(Timesheet $timesheet)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTimesheetRequest  $request
     * @param  \App\Models\Timesheet  $timesheet
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTimesheetRequest $request, Timesheet $timesheet)
    {
        $params = $request->only('tasks', 'difficulties', 'todo', 'date');

        $this->timesheetService->update(Auth::user(), $timesheet, $params);
        return $this->successResponse('Timesheets updated', 'Success');
    }

    public function checkIn()
    {
        $timesheet = $this->timesheetService->checkIn(Auth::user(), Carbon::now()->toDateString(), Carbon::now()->toTimeString());
        return $this->successResponse('Checked in time: ' . $timesheet->date . ' ' . $timesheet->check_in, 'Success');
    }

    public function checkOut() {
        $timesheet = $this->timesheetService->checkOut(Auth::user(), Carbon::now()->toDateString(), Carbon::now()->toTimeString());
        if (!$timesheet) {
            return $this->errorResponse('You have not checked in yet!', 'Failed to check out', 400);
        }
        return $this->successResponse('Checked out time: ' . $timesheet->date . ' ' . $timesheet->check_in, 'Success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Timesheet  $timesheet
     * @return \Illuminate\Http\Response
     */
    public function destroy(Timesheet $timesheet)
    {
        //
    }
}
