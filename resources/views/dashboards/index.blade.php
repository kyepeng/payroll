@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Timesheet Dashboard</h2>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="alert alert-info">
            <h4>Timesheets with Hours Worked Less Than 9</h4>
            <p>Total: {{ 0 }}</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 margin-tb">
        <table class="table table-bordered table-responsive" id="timesheet_table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>User</th>
                    <th>Date</th>
                    <th>Hours Worked</th>
                    <th>Task Details</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {

        $('#timesheet_table thead tr').clone(true).appendTo('#timesheet_table thead');
        $('#timesheet_table thead tr:eq(1) th').each(function(i) {
            var title = $(this).text();
            $(this).html('<input type="text" placeholder="Search ' + title + '" />');

            $('input', this).on('keyup change', function() {
                if (table.column(i).search() !== this.value) {
                    table
                        .column(i)
                        .search(this.value, true, false)
                        .draw();
                }
            });
        });

        // Initialize DataTable
        var table = $('#timesheet_table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: "{{ route('dashboards.index') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'user',
                    name: 'User'
                },
                {
                    data: 'date',
                    name: 'Date'
                },
                {
                    data: 'hours_worked',
                    name: 'Hours Worked'
                },
                {
                    data: 'description',
                    name: 'Task Details'
                },
                {
                    data: 'created_at',
                    name: 'Created At'
                },
                {
                    data: 'updated_at',
                    name: 'Updated At'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });
    });
</script>
@endsection