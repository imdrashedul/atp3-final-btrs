@extends('layouts.admin')

@section('title', 'BTRS - Update Bus')

@section('body')
    <div class="card">
        <div class="card-header">
            <i class="fa fa-table"></i> Update Bus
            <a href="{{ route('buses') }}" class="btn btn-danger btn-sm pull-right">Cancel</a>
        </div>
        <div class="card-body">
            <form method="post" action="{{ route('busesedit', ['id' => $bus->id]) }}">
                @csrf
                @if(user()->role->name=='busmanager')
                    <input type="hidden" name="operator" value="{{user()->id}}">
                @else
                    <div class="form-group row">
                        <label for="inputName" class="col-sm-2 col-form-label">Operator</label>
                        <div class="col-sm-10">
                            <select name="operator" id="inputOperator" class="form-control @error('operator') is-invalid @enderror">
                                @if(!empty($busmanager))
                                    <option value="">Select Bus Operator</option>
                                    @foreach($busmanager as $operator)
                                        <option value="{{$operator->id}}" {{ old('operator', $bus->operatorid) == $operator->id ? 'selected' : '' }}>{{$operator->company}}</option>
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
                    <label for="inputName" class="col-sm-2 col-form-label">Name</label>
                    <div class="col-sm-10">
                        <input type="text" name="name" class="form-control  @error('name') is-invalid @enderror" value="{{ old('name', $bus->name) }}" id="inputName" placeholder="Enter name ">
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputRegistration" class="col-sm-2 col-form-label">Registration No</label>
                    <div class="col-sm-10">
                        <input type="text" name="registration" class="form-control @error('registration') is-invalid @enderror" value="{{ old('registration', $bus->registration) }}" id="inputRegistration" placeholder="Enter Registration No">
                        @error('registration')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="inputSeatsRow" class="col-sm-2 col-form-label">Seats Row</label>
                    <div class="col-sm-10">
                        <input type="text" name="seatsrow" class="form-control @error('seatsrow') is-invalid @enderror" value="{{ old('seatsrow', $bus->seats_row) }}" id="inputSeatsRow" placeholder="Number of Seats Row">
                        @error('seatsrow')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="inputSeatsColumn" class="col-sm-2 col-form-label">Seats Column</label>
                    <div class="col-sm-10">
                        <input type="text" name="seatscolumn" class="form-control @error('seatscolumn') is-invalid @enderror" value="{{ old('seatscolumn', $bus->seats_column) }}" id="inputSeatsColumn" placeholder="Number of Seats Column">
                        @error('seatscolumn')
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