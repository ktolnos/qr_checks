@extends('app')

@section('content')
    <a class="btn btn-primary" href="{{url('checks/add')}}">Add check</a>
@if (count($checks) > 0)
    <div class="panel panel-default">
        <div class="panel-heading">
            Checks
        </div>
        <div class="panel-body">
            <table class="table table-striped task-table">

                <!-- Table Headings -->
                <thead>
                <th>Check</th>
                <th>&nbsp;</th>
                </thead>

                <!-- Table Body -->
                <tbody>
                @foreach ($checks as $check)
                    <tr>
                        <td class="table-text">
                            <div>{{ $check->id }}</div>
                        </td>

                        <td>
                            <!-- TODO: Delete Button -->
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@else
    <div class="panel-heading">
        No Checks
    </div>
@endif
@endsection