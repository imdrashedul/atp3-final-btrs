@extends('layouts.admin')

@section('title', 'BTRS - Manage Bus Manager')

@section('body')
    <div class="card">
        <div class="card-header">
            <i class="fa fa-table"></i> Bus Managers
        </div>
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
                @php
                $colspan = 4;
                if(user()->role->name!='busmanager') $colspan++;
                if(user_has_access(['managebusmanagerpermission', 'editbusmanager', 'removebusmanager'])) $colspan++;
                @endphp
                <table class="table table-bordered table-striped mb-0" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Registered</th>
                        <th>Company</th>
                        @if(user_has_access(['managebusmanagerpermission', 'editbusmanager', 'removebusmanager']))
                        <th width="20%">Action</th>
                        @endif
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
                                @if(user_has_access(['managebusmanagerpermission', 'editbusmanager', 'removebusmanager']))
                                <td style="text-align: center;">
                                    @if(user_has_access(['managebusmanagerpermission']))
                                    <a class="btn btn-secondary btn-sm" href="{{ route('managerole_permissionuser', ['id' => $user->id]) }}">Permissions</a>
                                    @endif
                                    @if(user_has_access(['editbusmanager']))
                                    <a class="btn btn-primary btn-sm" href="{{ route('busmanageredit', ['id' => $user->id]) }}">Update</a>
                                    @endif
                                    @if(user_has_access(['removebusmanager']))
                                    <a class="btn btn-danger btn-sm" href="{{ route('busmanagerdelete', ['id' => $user->id]) }}">Remove</a>
                                    @endif
                                </td>
                                @endif
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="{{$colspan}}">No results found</td>
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
                                    const $tr = __tr.clone();
                                    if(v.length>0) {
                                        $.each(v, function (_i, _v) {
                                            const $td = __td.clone().append($.isPlainObject(_v) && 'text' in _v ? _v.text : _v);
                                            if($.isPlainObject(_v) && 'css' in _v ) $td.css(_v.css);
                                            $tr.append($td)
                                        });
                                    }
                                    __tbody.append($tr);
                                });
                            } else {
                                __tbody.append(__tr.clone().append(__td.clone().text(
                                    'No results found'
                                ).attr({ colspan: {{$colspan}} })));
                            }
                        }
                    });


                });
            });
        });
    </script>
@endsection