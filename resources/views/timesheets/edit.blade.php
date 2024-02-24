@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Edit Timesheet</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('timesheets.index') }}"> Back</a>
        </div>
    </div>
</div>
@if ($errors->any())
<div class="alert alert-danger">
    <strong>Whoops!</strong> There were some problems with your input.<br><br>
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

{!! Form::model($timesheet, ['method' => 'PATCH','route' => ['timesheets.update', $timesheet->id]]) !!}
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        @if(auth()->user()->hasRole('admin'))
        {!! Form::select('user_id', $users->pluck('name','id'), null, ['class' => 'form-control']) !!}
        @else
        {!! Form::hidden('user_id', auth()->user()->id) !!}
        {!! Form::text('name', auth()->user()->name, ['class' => 'form-control', 'readonly']) !!}
        @endif
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Date:</strong>
            {!! Form::date('date', null, ['class' => 'form-control', 'placeholder' => 'Date']) !!}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Hours Worked:</strong>
            {!! Form::number('hours_worked', null, ['class' => 'form-control', 'placeholder' => 'Hours Worked']) !!}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Task Details:</strong>
            {!! Form::textarea('description', null, ['class' => 'form-control', 'style' => 'height:150px', 'placeholder' => 'Task Description']) !!}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
        {!! Form::submit('Submit', ['class' => 'btn btn-primary']) !!}
    </div>
</div>
{!! Form::close() !!}
@endsection