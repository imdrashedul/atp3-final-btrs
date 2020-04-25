@extends('layouts.admin')

@section('title', 'BTRS - Manage User Permission')

@section('body')
    <div class="card">
        <div class="card-header">
            <i class="fa fa-table"></i> Permissions of {{ $user->name }}
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped mb-0" id="dataTable" width="100%" cellspacing="0">
                    <tbody>
                    @if($rolePermissions && count($rolePermissions)>0)
                        <form method="post" action="{{ route('managerole_permissionuser', ['id' => $user->id]) }}">
                        @csrf
                        @foreach($rolePermissions as $rolePermission)
                            <tr>
                                <td width="20px"><input type="checkbox" name="permissions[]" value="{{ $rolePermission->id }}" id="permission{{ $rolePermission->id }}" @if(in_array($rolePermission->id, $userPermissions)) checked @endif></td>
                                <td><label for="permission{{ $rolePermission->id }}">{{ ucfirst($rolePermission->details) }}</label></td>
                            </tr>
                        @endforeach
                            <tr>
                                <td></td>
                                <td><button type="submit" class="btn btn-success btn-sm">Save Changes</button></td>
                            </tr>
                        </form>
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