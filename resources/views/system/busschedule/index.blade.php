@extends('layouts.admin')

@section('title', 'BTRS - Bus Schedules')

@section('body')
    <div class="card">
        <div class="card-header">
            <i class="fa fa-table"></i> Bus Schedules
            @if(user_has_access(['addbusschedule']))
                <a href="{{ route('busscheduleadd') }}" class="btn btn-primary btn-sm pull-right">Add</a>
            @endif
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col col-sm-12 col-md-8 col-lg-10">
                    <input type="text" name="search" class="form-control" placeholder="Search using Operator, Bus, Route, Boarding">
                </div>
                <div class="col col-sm-12 col-md-4 col-lg-2">
                    <button id="search" class="btn btn-primary btn-block">Search</button>
                </div>
            </div>
            <div class="table-responsive">
                @php
                    $colspan = 6;
                    if(user()->role->name!='busmanager') $colspan++;
                    if(user_has_access(['editbusschedule', 'removebusschedule'])) $colspan++;
                @endphp
                <table class="table table-bordered table-striped mb-0" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        @if(user()->role->name!='busmanager')
                            <th>Operator</th>
                        @endif
                        <th>Bus</th>
                        <th>Route</th>
                        <th>Departure</th>
                        <th>Arrival</th>
                        <th>Fare</th>
                        <th>Boarding</th>
                        @if(user_has_access(['editbusschedule', 'removebusschedule']))
                            <th width="20%">Action</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>

                    @if($busschedules && count($busschedules)>0)
                        @foreach($busschedules as $busschedule)
                            <tr>
                                @if(user()->role->name!='busmanager')
                                    <td>{{ $busschedule->bus->operator->company }}</td>
                                @endif
                                <td>{{ $busschedule->bus->name . ' ['.$busschedule->bus->registration.']' }}</td>
                                <td>{{ $busschedule->route }}</td>
                                <td>{{ date_format(date_create($busschedule->departure),"j M Y g:i a") }}</td>
                                <td>{{ date_format(date_create($busschedule->arrival),"j M Y g:i a") }}</td>
                                <td style="text-align: right">{{ $busschedule->fare }} BDT</td>
                                <td>{{ $busschedule->boarding->name }}</td>
                                @if(user_has_access(['editbusschedule', 'removebusschedule']))
                                    <td style="text-align: center;">
                                        @if(user_has_access(['editbusschedule']))
                                            <a class="btn btn-primary btn-sm" href="{{ route('busscheduleedit', ['id' => $busschedule->id]) }}">Update</a>
                                        @endif
                                        @if(user_has_access(['removebusschedule']))
                                            <a class="btn btn-danger btn-sm" href="{{ route('busscheduledelete', ['id' => $busschedule->id]) }}">Remove</a>
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
                        url: '{{ route('ajax_search_busschedule') }}',
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