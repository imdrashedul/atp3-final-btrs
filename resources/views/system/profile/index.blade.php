@extends('layouts.admin')

@section('title', 'Profile')

@section('body')
<div class="card">
    <div class="card-header">
        User Profile
    </div>
    <div class="card-body">
        @if(user()->validated == '1')
            <form method="post">
                @csrf
                <div class="form-group row">
                    <label for="inputName" class="col-sm-2 col-form-label">Name</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="name" id="inputName" value="{{user()->name}}" placeholder="Enter full name">
                    </div>
                </div>
                @if(user()->roleid == roleid_by_name('busmanager'))
                    <div class="form-group row">
                        <label for="inputCompanyName" class="col-sm-2 col-form-label">Company Name</label>
                        <div class="col-sm-10">
                        <input type="text" class="form-control" name="company" id="inputCompanyName" value="{{user()->company}}" placeholder="Enter Company name">
                        </div>
                    </div>
                @endif
                <div class="form-group row">
                    <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                    <div class="col-sm-10">
                    <input type="email" class="form-control" name="email" id="inputEmail" value="{{user()->email}}" placeholder="Enter email address">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputPassword" class="col-sm-2 col-form-label">Password</label>
                    <div class="col-sm-10">
                    <input type="password" class="form-control" name="password" id="inputPassword" placeholder="Password">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="offset-sm-2 col-sm-10">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </div>
            </form>
        @else
            <table class="form">
                <tr>
                    <td>Name</td><td>:</td><td>{{user()->name}}</td>
                </tr>
                <tr>
                    <td>Email</td><td>:</td><td>{{user()->email}}</td>
                </tr>
                @if(user()->roleid == roleid_by_name('busmanager'))
                    <tr>
                        <td>Company</td><td>:</td><td>{{user()->company}}</td>
                    </tr>
                @endif
            </table>
        @endif
    </div>
</div>
@endsection

