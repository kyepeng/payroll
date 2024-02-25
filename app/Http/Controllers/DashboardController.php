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
            // Loop through each column
            for ($i = 0; $i < count($request->columns); $i++) {
                $column_search_value = $request->columns[$i]['search']['value'];

                // If there is a search value for this column
                if ($column_search_value != '') {
                    $column_name = $request->columns[$i]['name'];

                    // Apply the search to the query
                    $query->where($column_name, 'like', '%' . $column_search_value . '%');
                }
            }
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
                ->filter(function ($query) use ($request) {
                    if ($keyword = $request->input('search.value')) {
                        return $query->whereHas('user', function ($subQuery) use ($keyword) {
                            return $subQuery->where('name', 'LIKE', '%' . $keyword . '%');
                        });
                    }
                })
                ->make(true);
        }
        return view('dashboards.index');
    }
}
