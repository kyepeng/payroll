<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Timesheet;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DataTables;

class DashboardController extends Controller
{
    /**
     * Construct the RoleController.
     */
    function __construct()
    {
        $this->middleware('role:admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Timesheet::query()->latest()->with('user');
            $data = $query->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('user', function ($row) {
                    return $row->user->name;
                })
                ->addColumn('action', function ($row) {
                    $show = '<a href="' . route('timesheets.show', $row->id) . '" class="edit btn btn-success btn-sm">Show</a>';
                    $edit = '<a href="' . route('timesheets.edit', $row->id) . '" class="edit btn btn-primary btn-sm">Edit</a>';
                    $delete = '<form action="' . route('timesheets.destroy', $row->id) . '" method="POST">
                                        ' . csrf_field() . '
                                        ' . method_field("DELETE") . '
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </form>';
                    return $show . ' ' . $edit . ' ' . $delete;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('dashboards.index');
    }
}
