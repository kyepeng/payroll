<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTimesheetRequest;
use App\Http\Requests\UpdateTimesheetRequest;
use App\Timesheet;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class TimesheetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:timesheet-list|timesheet-create|timesheet-edit|timesheet-delete', ['only' => ['index','show']]);
         $this->middleware('permission:timesheet-create', ['only' => ['create','store']]);
         $this->middleware('permission:timesheet-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:timesheet-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->hasRole('admin')) {
            $timesheets = Timesheet::latest()->paginate(5);
        } else {
            $timesheets = Timesheet::latest()
            ->where('user_id',Auth::user()->id)
            ->paginate(5);
        }
        return view('timesheets.index',compact('timesheets'))
        ->with('i', (request()->input('page', 1) - 1) * 5);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $users = User::all();
        return view('timesheets.create', compact('users'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\StoreTimesheetRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTimesheetRequest $request)
    {
        Timesheet::create($request->all());
    
        return redirect()->route('timesheets.index')
                        ->with('success','Timesheet created successfully.');
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\Timesheet  $timesheet
     * @return \Illuminate\Http\Response
     */
    public function show(Timesheet $timesheet)
    {
        return view('timesheets.show',compact('timesheet'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Timesheet  $timesheet
     * @return \Illuminate\Http\Response
     */
    public function edit(Timesheet $timesheet)
    {
        $users = User::all();
        return view('timesheets.edit',compact('timesheet'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\UpdateTimesheetRequest  $request
     * @param  \App\Timesheet  $timesheet
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTimesheetRequest $request, Timesheet $timesheet)
    {
        $timesheet->update($request->validated());
    
        return redirect()->route('timesheets.index')
                        ->with('success','Timesheet updated successfully');
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Timesheet  $timesheet
     * @return \Illuminate\Http\Response
     */
    public function destroy(Timesheet $timesheet)
    {
        $timesheet->delete();
    
        return redirect()->route('timesheets.index')
                        ->with('success','Timesheet deleted successfully');
    }
}
