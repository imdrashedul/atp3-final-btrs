@extends('layouts.admin')

@section('title', 'BTRS - Manage Role Permissions')

@section('body')
    <div class="card">
        <div class="card-header">
            <i class="fa fa-table"></i> Permission Lists of Role {{ ucfirst($role->name) }}
            <a href="{{ route('managerole_permissionadd', ['id' => $role->id]) }}" class="btn btn-primary btn-sm pull-right">Add</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped mb-0" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>Permission</th>
                        <th>Key</th>
                        <th width="20%">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                        @if($role->permissions && count($role->permissions)>0)
                            @foreach($role->permissions as $permission)
                                <tr>
                                    <td>{{ $permission->details }}</td>
                                    <td>{{ $permission->permission }}</td>
                                    <td style="text-align: center;">
                                        <a class="btn btn-primary btn-sm" href="{{ route('managerole_permissionedit', ['id' => $permission->id]) }}">Edit</a>
                                        <a class="btn btn-danger btn-sm" href="{{ route('managerole_permissiondelete', ['id' => $permission->id]) }}">Remove</a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                        <tr>
                            <td colspan="3">No results found</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection