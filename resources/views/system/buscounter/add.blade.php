@extends('layouts.admin')

@section('title', 'BTRS - Add Bus Counter')

@section('body')
    <div class="card">
        <div class="card-header">
            <i class="fa fa-table"></i> Add New Bus Counter
            <a href="{{ route('buscounter') }}" class="btn btn-danger btn-sm pull-right">Cancel</a>
        </div>
        <div class="card-body">
            <form method="post" action="{{ route('buscounteradd') }}">
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
                                      <option value="{{$operator->id}}">{{$operator->company}}</option>
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
                    <label for="inputCounter" class="col-sm-2 col-form-label">Name</label>
                    <div class="col-sm-10">
                        <input type="text" name="name" class="form-control  @error('name') is-invalid @enderror" value="{{ old('name') }}" id="inputCounter" placeholder="Enter name ">
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputPassword" class="col-sm-2 col-form-label">Location</label>
                    <div class="col-sm-10">
                        <input type="text" name="location" class="form-control @error('location') is-invalid @enderror" id="inputLocation" placeholder="Enter location">
                        @error('location')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-10 offset-sm-2">
                        <button type="submit" class="btn btn-primary">Add Bus Counter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection