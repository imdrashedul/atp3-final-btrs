@extends('layouts.admin')

@section('title', 'BTRS - Validate User')

@section('body')
    <div class="card">
        <div class="card-header">
            <i class="fa fa-table"></i> Awaiting Validation Lists
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped mb-0" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Company</th>
                        <th>Registered</th>
                        <th width="20%">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($users && count($users)>0)
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->company }}</td>
                                <td>{{ date_format(date_create($user->registered),"j M Y g:i a") }}</td>
                                <td style="text-align: center;">
                                    <a class="btn btn-primary btn-sm" href="{{ route('validation_validate', ['id' => $user->id]) }}">Validate</a>
                                    <a class="btn btn-danger btn-sm" href="{{ route('validation_delete', ['id' => $user->id]) }}">Remove</a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5">No results found</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection