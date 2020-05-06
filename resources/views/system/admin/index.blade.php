@extends('layouts.admin')

@section('title', 'BTRS - Manage Admin')

@section('body')
    <div class="card">
        <div class="card-header">
            <i class="fa fa-table"></i> Admin
            <a href="{{ route('adminadd') }}" class="btn btn-primary btn-sm pull-right">Add</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped mb-0" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
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
                                <td>{{ date_format(date_create($user->registered),"j M Y g:i a") }}</td>
                                <td style="text-align: center;">
                                    <a class="btn btn-secondary btn-sm" href="{{ route('managerole_permissionuser', ['id' => $user->id]) }}">Permissions</a>
                                    <a class="btn btn-primary btn-sm" href="{{ route('adminedit', ['id' => $user->id]) }}">Update</a>
                                    <a class="btn btn-danger btn-sm" href="{{ route('admindelete', ['id' => $user->id]) }}">Remove</a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4">No results found</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection