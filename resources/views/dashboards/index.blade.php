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
        <div class="alert alert-danger" id="lessThanThresholdAlert">
            <h5>Timesheets < 8 Hours Worked</h5>
            <h4>Total: <span id="lessThanThresholdCount">0</span></h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="table-responsive">
            <table class="table table-bordered" id="timesheet_table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Date</th>
                        <th>Hours Worked</th>
                        <th>Task Details</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
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
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'csv',
                    exportOptions: {
                        columns: ':not(:last)'
                    }
                },
                {
                    extend: 'excel',
                    exportOptions: {
                        columns: ':not(:last)'
                    }
                },
                {
                    extend: 'pdf',
                    exportOptions: {
                        columns: ':not(:last)'
                    }
                }
            ],
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: "{{ route('dashboards.index') }}",
            columns: [{
                    data: 'user',
                    name: 'user'
                },
                {
                    data: 'date',
                    name: 'date'
                },
                {
                    data: 'hours_worked',
                    name: 'hours_worked'
                },
                {
                    data: 'description',
                    name: 'description'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ],
            search: {
                "regex": true
            }
        });

        table.on('draw', function() {
            var data = table.rows({
                filter: 'applied'
            }).data();
            var lessThanThresholdCount = 0;

            for (var i = 0; i < data.length; i++) {
                if (data[i].hours_worked < 8) {
                    lessThanThresholdCount++;
                }
            }

            $('#lessThanThresholdCount').text(lessThanThresholdCount);
        });
    });
</script>
@endsection