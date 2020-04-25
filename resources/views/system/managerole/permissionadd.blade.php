@extends('layouts.admin')

@section('title', 'BTRS - Add New Role Permission')

@section('body')
    <div class="card">
        <div class="card-header">
            <i class="fa fa-table"></i> Add New Permission [ {{ ucfirst($role->name) }} ]
        </div>
        <div class="card-body">
                <form method="post" action="{{ route('managerole_permissionadd', ['id' => $role->id]) }}">
                    @csrf
                    <div class="form-group row">
                        <label for="key" class="col-sm-2 col-form-label">Key</label>
                        <div class="col-sm-10">
                            <input type="text" name="key" class="form-control @error('key') is-invalid @enderror" id="key" value="{{old('key')}}" placeholder="Enter Permission Key">
                            @error('key')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="detail" class="col-sm-2 col-form-label">Detail</label>
                        <div class="col-sm-10">
                            <input type="text" name="detail" class="form-control @error('detail') is-invalid @enderror" id="detail" value="{{old('detail')}}" placeholder="Enter Permission Detail">
                            @error('detail')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-10 offset-sm-2">
                            <button type="submit" class="btn btn-primary">Add Permission</button>
                        </div>
                    </div>
                </form>
        </div>
    </div>
@endsection
