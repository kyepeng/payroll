@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Timesheet</h2>
        </div>
        <div class="pull-right">
            @can('timesheet-create')
            <a class="btn btn-success" href="{{ route('timesheets.create') }}"> Create New Timesheet</a>
            @endcan
        </div>
    </div>
</div>
@if ($message = Session::get('success'))
<div class="alert alert-success">
    <p>{{ $message }}</p>
</div>
@endif
<table class="table table-bordered">
    <tr>
        <th>No</th>
        <th>User</th>
        <th>Date</th>
        <th>Hours Worked</th>
        <th>Task Details</th>
        <th>Created At</th>
        <th>Updated At</th>
        <th width="280px">Action</th>
    </tr>
    @foreach ($timesheets as $timesheet)
    <tr>
        <td>{{ ++$i }}</td>
        <td>{{ $timesheet->user->name }}</td>
        <td>{{ $timesheet->date }}</td>
        <td>{{ $timesheet->hours_worked }}</td>
        <td>{{ $timesheet->description }}</td>
        <td>{{ $timesheet->created_at }}</td>
        <td>{{ $timesheet->updated_at }}</td>
        <td>
            <form action="{{ route('timesheets.destroy',$timesheet->id) }}" method="POST">
                <a class="btn btn-info" href="{{ route('timesheets.show',$timesheet->id) }}">Show</a>
                @can('timesheet-edit')
                <a class="btn btn-primary" href="{{ route('timesheets.edit',$timesheet->id) }}">Edit</a>
                @endcan
                @csrf
                @method('DELETE')
                @can('timesheet-delete')
                <button type="submit" class="btn btn-danger">Delete</button>
                @endcan
            </form>
        </td>
    </tr>
    @endforeach
</table>
{!! $timesheets->links() !!}

@endsection