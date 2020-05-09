@extends('layouts.admin')

@section('title', 'BTRS - Manage Bus Manager')

@section('body')
    <div class="card">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col col-sm-12 col-md-8 col-lg-10">
                    <input type="text" name="search" class="form-control" placeholder="Search using Name or Email">
                </div>
                <div class="col col-sm-12 col-md-4 col-lg-2">
                    <button id="search" class="btn btn-primary btn-block">Search</button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped mb-0" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Registered</th>
                        <th>Company</th>
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
                                <td>{{ $user->company }}</td>
                                <td style="text-align: center;">
                                    @if(user_has_role(['admin', 'super','supportstaff']) && user_has_access(['managebusmanagerpermission']))
                                    <a class="btn btn-secondary btn-sm" href="{{ route('managerole_permissionuser', ['id' => $user->id]) }}">Permissions</a>
                                    @endif
                                    <a class="btn btn-primary btn-sm" href="{{ route('busmanageredit', ['id' => $user->id]) }}">Update</a>
                                    <a class="btn btn-danger btn-sm" href="{{ route('busmanagerdelete', ['id' => $user->id]) }}">Remove</a>
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

@section('javascript')
    <script type="text/javascript">
        jQuery(function ($) {
            $(document).ready(function () {
                $('body').on('click', '#search', function (e) {
                    e.preventDefault();
                    const search = $('input[type="text"][name="search"]').val();

                    $.ajax({
                        url: '{{ route('ajax_search_busmanager') }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            search: search
                        },
                        dataType: 'JSON',
                        success: function (data) {
                            const __tbody = $('table>tbody');
                            const __tr = $('<tr/>');
                            const __td = $('<td/>');
                            __tbody.html('');
                            if(Array.isArray(data) && data.length>0) {
                                $.each(data, function (i, v) {
                                   __tbody.append(__tr.clone().append(__td.clone().text(
                                       v[0]
                                   )).append(__td.clone().text(
                                       v[1]
                                   )).append(__td.clone().text(
                                       v[2]
                                   )).append(__td.clone().text(
                                       v[3]
                                   )).append(__td.clone().html(
                                       v[4]
                                   ).css({ 'text-align' : 'center'})));
                                });
                            } else {
                                __tbody.append(__tr.clone().append(__td.clone().text(
                                    'No results found'
                                ).attr({ colspan: 5 })));
                            }
                        }
                    });


                });
            });
        });
    </script>
@endsection