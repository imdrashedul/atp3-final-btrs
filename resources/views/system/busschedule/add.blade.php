@extends('layouts.admin')

@section('title', 'BTRS - Add Bus Schedule')

@section('body')
    <div class="card">
        <div class="card-header">
            <i class="fa fa-table"></i> Add Bus Schedule
            <a href="{{ route('busschedule') }}" class="btn btn-danger btn-sm pull-right">Cancel</a>
        </div>
        <div class="card-body">
            <form method="post" action="{{ route('busscheduleadd') }}">
                @csrf
                @if(user()->roleid==roleid_by_name('busmanager'))
                    <input type="hidden" name="operator" value="{{user()->id}}">
                @else
                    <div class="form-group row">
                        <label for="inputName" class="col-sm-2 col-form-label">Operator</label>
                        <div class="col-sm-10">
                            <select name="operator" id="inputOperator" class="form-control @error('operator') is-invalid @enderror">
                                @if(!empty($busmanager))
                                    <option value="">Select Bus Operator</option>
                                    @foreach($busmanager as $operator)
                                        <option value="{{$operator->id}}" {{ old('operator') ==  $operator->id ? 'selected' : ''}}>{{$operator->company}}</option>
                                    @endforeach
                                @else
                                    <option value="">No Operator Found</option>
                                @endif
                            </select>
                            @error('operator')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                @endif

                <div class="form-group row">
                    <label for="inputBus" class="col-sm-2 col-form-label">Bus</label>
                    <div class="col-sm-10">
                        <select name="bus" id="inputBus" class="form-control @error('bus') is-invalid @enderror">
                            @if(user()->roleid==roleid_by_name('busmanager'))
                                @if(!empty($buses))
                                    <option value="">Select Bus</option>
                                    @foreach($buses as $bus)
                                        <option value="{{$bus->id}}" {{ old('bus') ==  $bus->id ? 'selected' : ''}}>{{$bus->name . '['.$bus->registration.']'}}</option>
                                    @endforeach
                                @else
                                    <option value="">No Bus Found</option>
                                @endif
                            @else
                                <option value="">Select Bus Operator First</option>
                            @endif
                        </select>
                        @error('bus')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="inputCounter" class="col-sm-2 col-form-label">Boarding</label>
                    <div class="col-sm-10">
                        <select name="boarding" id="inputCounter" class="form-control @error('boarding') is-invalid @enderror">
                            @if(user()->roleid==roleid_by_name('busmanager'))
                                @if(!empty($counters))
                                    <option value="">Select Bus Counter</option>
                                    @foreach($counters as $counter)
                                        <option value="{{$counter->id}}" {{ old('boarding') ==  $counter->id ? 'selected' : ''}}>{{$counter->name . '['.$counter->location.']'}}</option>
                                    @endforeach
                                @else
                                    <option value="">No Bus Counter Found</option>
                                @endif
                            @else
                                <option value="">Select Bus Operator First</option>
                            @endif
                        </select>
                        @error('boarding')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="inputFrom" class="col-sm-2 col-form-label">From</label>
                    <div class="col-sm-10">
                        <input type="text" name="from" class="form-control @error('from') is-invalid @enderror" value="{{ old('from') }}" id="inputFrom" placeholder="Enter route (from)">
                        @error('from')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="inputTo" class="col-sm-2 col-form-label">To</label>
                    <div class="col-sm-10">
                        <input type="text" name="to" class="form-control @error('to') is-invalid @enderror" value="{{ old('to') }}" id="inputTo" placeholder="Enter route (to)">
                        @error('to')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="inputDeparture" class="col-sm-2 col-form-label">Departure</label>
                    <div class="col-sm-10">
                        <input type="datetime-local" name="departure" class="form-control @error('departure') is-invalid @enderror" value="{{ old('departure') }}" id="inputDeparture">
                        @error('departure')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="inputArrival" class="col-sm-2 col-form-label">Arrival</label>
                    <div class="col-sm-10">
                        <input type="datetime-local" name="arrival" class="form-control @error('arrival') is-invalid @enderror" value="{{ old('arrival') }}" id="inputArrival">
                        @error('arrival')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="inputFare" class="col-sm-2 col-form-label">Fare</label>
                    <div class="col-sm-10">
                        <input type="text" name="fare" class="form-control @error('fare') is-invalid @enderror" value="{{ old('fare') }}" id="inputFare" placeholder="Enter fare per seat">
                        @error('fare')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-10 offset-sm-2">
                        <button type="submit" class="btn btn-primary">Add Schedule</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('javascript')
    @if(user()->roleid!=roleid_by_name('busmanager'))
        <script type="text/javascript">
            jQuery(function ($) {
                $(document).ready(function () {
                    $('body').on('change', 'select#inputOperator', function(e) {
                        const $parent = $(this);
                        const $childCounter = $('select#inputCounter');
                        const $childBus = $('select#inputBus');
                        const selected = $parent.val();

                        if(selected.length) {
                            renderCounter($childBus, selected, '', '{{ route('ajax_buses_by_operator') }}', 'Select Bus', 'No Bus Found');
                            renderCounter($childCounter, selected, '', '{{ route('ajax_buscounter_by_operator') }}', 'Select Bus Counter', 'No Bus Counter Found');
                        } else {
                            clearElement($childBus);
                            clearElement($childCounter);
                            appendOption($childBus, {
                                value : '',
                                text : 'Select Bus Operator First'
                            });
                            appendOption($childCounter, {
                                value : '',
                                text : 'Select Bus Operator First'
                            });
                        }
                    });

                    @if(old('operator', '')!='')
                    clearElement($('select#inputBus'));
                    renderCounter($('select#inputBus'), '{{old('operator')}}', '{{old('bus')}}', '{{ route('ajax_buses_by_operator') }}', 'Select Bus', 'No Bus Found');
                    clearElement($('select#inputCounter'));
                    renderCounter($('select#inputCounter'), '{{old('operator')}}', '{{old('boarding')}}', '{{ route('ajax_buscounter_by_operator') }}', 'Select Bus Counter', 'No Bus Counter Found');
                    @endif
                });

                function renderCounter($child, selected, select, route, def, nofound) {
                    select = select || '';
                    $.ajax({
                        url: route,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            operator: selected
                        },
                        dataType: 'JSON',
                        success: function (data) {
                            clearElement($child);
                            if(Array.isArray(data) && data.length>0) {
                                appendOption($child, {
                                    value : '',
                                    text : def
                                });
                                $.each(data, function (i, v) {
                                    if(select.length>0) { v.select = (v.value == select); console.log(v.value == select, select, v.value);}
                                    appendOption($child, v);
                                });
                            } else {
                                appendOption($child, {
                                    value : '',
                                    text : nofound
                                });
                            }
                        }
                    });
                }

                function clearElement($element) {
                    $element.html('');
                }

                function appendOption($parent, obj) {
                    const $option = $('<option/>');
                    $parent.append(
                        $option.clone()
                            .attr({ value : obj.value, selected : ( 'select' in obj && obj.select ) })
                            .text(obj.text)
                    );
                }
            });
        </script>
    @endif
@endsection