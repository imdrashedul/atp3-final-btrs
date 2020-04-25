@extends('layouts.admin')

@section('title', 'BTRS - Manage Role')

@section('body')
    <div class="card">
        <div class="card-header">
            <i class="fa fa-table"></i> Roles Lists
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped mb-0" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>Role</th>
                        <th width="20%">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($roles && count($roles)>0)
                    @foreach($roles as $role)
                    <tr>
                        <td>{{ ucfirst($role->name) }}</td>
                        <td style="text-align: center;">
                            <a class="btn btn-primary btn-sm" href="{{ route('managerole_permission', ['id' => $role->id]) }}">Permissions</a>
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="2">No results found</td>
                    </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection