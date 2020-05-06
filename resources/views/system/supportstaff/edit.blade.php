@extends('layouts.admin')

@section('title', 'BTRS - Edit Support Staff')

@section('body')
    <div class="card">
        <div class="card-header">
            <i class="fa fa-table"></i> Updating {{ $user->name }}
            <a href="{{ route('supportstaff') }}" class="btn btn-danger btn-sm pull-right">Cancel</a>
        </div>
        <div class="card-body">
            <form method="post" action="{{ route('supportstaffedit', ['id' => $user->id]) }}">
                @csrf
                <div class="form-group row">
                    <label for="inputName" class="col-sm-2 col-form-label">Name</label>
                    <div class="col-sm-10">
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" id="inputName" placeholder="Enter full name">
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                    <div class="col-sm-10">
                        <input type="email" name="email" class="form-control  @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" id="inputEmail" placeholder="Enter email address">
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputPassword" class="col-sm-2 col-form-label">Password</label>
                    <div class="col-sm-10">
                        <input type="password" name="password" class="form-control @error('email') is-invalid @enderror" id="inputPassword" placeholder="Enter a new password">
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