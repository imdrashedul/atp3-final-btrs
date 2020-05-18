@extends('layouts.admin')

@section('title', 'BTRS - Edit Counter Staff')

@section('body')
    <div class="card">
        <div class="card-header">
            <i class="fa fa-table"></i> Modify Counter Staff
            <a href="{{ route('counterstaff') }}" class="btn btn-danger btn-sm pull-right">Cancel</a>
        </div>
        <div class="card-body">
            <form method="post" action="{{ route('counterstaffedit', ['id' => $counterstaff->id]) }}">
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
                                    <option value="{{$operator->id}}" {{ old('operator', $counterstaff->operatorid) ==  $operator->id ? 'selected' : ''}}>{{$operator->company}}</option>
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
                    <label for="inputCounter" class="col-sm-2 col-form-label">Counter</label>
                    <div class="col-sm-10">
                        <select name="counter" id="inputCounter" class="form-control @error('counter') is-invalid @enderror">
                            @if(user()->roleid==roleid_by_name('busmanager'))
                                @if(!empty($counters))
                                    <option value="">Select Bus Counter</option>
                                    @foreach($counters as $counter)
                                        <option value="{{$counter->id}}" {{ old('counter', $counterstaff->counterid) ==  $counter->id ? 'selected' : ''}}>{{$counter->name . '['.$counter->location.']'}}</option>
                                    @endforeach
                                @else
                                    <option value="">No Bus Counter Found</option>
                                @endif
                            @else
                                <option value="">Select Bus Operator First</option>
                            @endif
                        </select>
                        @error('counter')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="inputName" class="col-sm-2 col-form-label">Name</label>
                    <div class="col-sm-10">
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $counterstaff->name) }}" id="inputName" placeholder="Enter name">
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                    <div class="col-sm-10">
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $counterstaff->email) }}" id="inputEmail" placeholder="Enter email address">
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputPassword" class="col-sm-2 col-form-label">Password</label>
                    <div class="col-sm-10">
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="inputPassword" placeholder="Enter password">
                        @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-10 offset-sm-2">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
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
                    const $child = $('select#inputCounter');
                    const selected = $parent.val();

                    if(selected.length) {
                        renderCounter($child, selected);
                    } else {
                        clearElement($child);
                        appendOption($child, {
                            value : '',
                            text : 'Select Bus Operator First'
                        });
                    }
                });

                @if(old('operator', $counterstaff->operatorid)!='')
                clearElement($('select#inputCounter'));
                renderCounter($('select#inputCounter'), '{{old('operator', $counterstaff->operatorid)}}', '{{old('counter', $counterstaff->counterid)}}');
                @endif
            });

            function renderCounter($child, selected, select) {
                select = select || '';
                $.ajax({
                    url: '{{ route('ajax_buscounter_by_operator') }}',
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
                                text : 'Select Bus Counter'
                            });
                            $.each(data, function (i, v) {
                                if(select.length>0) { v.select = (v.value == select); }
                                appendOption($child, v);
                            });
                        } else {
                            appendOption($child, {
                                value : '',
                                text : 'No Bus Counter Found'
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