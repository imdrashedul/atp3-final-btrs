@extends('layouts.admin')

@section('title', 'BTRS - Dashboard')

@section('body')
@if(user()->validated!==1)
<div class="card">
    <div class="card-header">
        Pending Validation !!
    </div>
    <div class="card-body">
        <p>
            You are not validate yet.<br>
            Please keep patient until you got validated by our authority.<br>
            Once you got validated you can enjoy all the features of our system.<br><br>
            -Thank you
        </p>
    </div>
</div>
@endif
@endsection