<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Bus Ticket Reservation System - Login</title>
    <!-- Bootstrap core CSS-->
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Custom fonts for this template-->
    <link href="{{ asset('assets/vendor/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
    <!-- Custom styles for this template-->
    <link href="{{ asset('assets/css/sb-admin.css') }}" rel="stylesheet">
</head>

<body class="bg-dark">
<div class="container">
    <div class="card card-login mx-auto mt-5">
        <div class="card-header">Login Here</div>
        <div class="card-body">
        @if(Session::has('register'))
            <div class="alert alert-success" role="alert">
                    {{ Session::get('register')}}
            </div>
        @endif
        @php $registered = Session::has('register_email') ? Session::get('register_email') : '' @endphp
            <form method=post>
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="inputEmail">Email</label>
                    <input class="form-control @error('email') is-invalid @enderror" name="email" id="inputEmail" type="email" value="{{ old('email', $registered) }}" placeholder="Enter email">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="inputPassword">Password</label>
                    <input class="form-control  @error('password') is-invalid @enderror" name="password" id="inputPassword" type="password" placeholder="Password">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary btn-block">Login</button>
            </form>
            <div class="text-center">
                <a class="d-block small mt-3" href="{{ route('register') }}">Register as a Company</a>
            </div>
        </div>
    </div>
</div>
<!-- Bootstrap core JavaScript-->
<script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- Core plugin JavaScript-->
<script src="{{ asset('assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
</body>

</html>