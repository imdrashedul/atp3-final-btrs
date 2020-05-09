<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>@yield('title', 'BTRS - Dashboard')</title>
    <!-- Bootstrap core CSS-->
    <link href="{{ asset('/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Custom fonts for this template-->
    <link href="{{ asset('/assets/vendor/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
    <!-- Custom styles for this template-->
    <link href="{{ asset('/assets/css/sb-admin.css') }}" rel="stylesheet">
</head>

<body class="fixed-nav sticky-footer bg-dark" id="page-top">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
    <a class="navbar-brand" href="/system">Bus Ticket Reservation System</a>
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav navbar-sidenav" id="exampleAccordion">
            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Dashboard">
                <a class="nav-link" href="{{ route('system') }}">
                    <i class="fa fa-fw fa-dashboard"></i>
                    <span class="nav-link-text">Dashboard</span>
                </a>
            </li>
            @if(user()->validated==1)
                @if(user_has_role(['admin', 'super', 'supportstaff']) && user_has_access(['verifybusmanager']))
                <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Validation">
                    <a class="nav-link" href="{{ route('validation') }}">
                        <i class="fa fa-fw fa-check-circle-o"></i>
                        <span class="nav-link-text">Validation ({{ App\User::where('validated', 0)->count() }})</span>
                    </a>
                </li>
                @endif

                @if(user_has_role(['super']))
                    <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Support Staff">
                        <a class="nav-link" href="{{ route('admin') }}">
                            <i class="fa fa-fw fa-bullhorn"></i>
                            <span class="nav-link-text">Admin</span>
                        </a>
                    </li>
                @endif

                @if(user_has_role(['super','admin']) && user_has_access(['managesupportstaffs']))
                <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Support Staff">
                    <a class="nav-link" href="{{ route('supportstaff') }}">
                      <i class="fa fa-fw fa-bullhorn"></i>
                      <span class="nav-link-text">Support Staff</span>
                    </a>
                </li>
                @endif

                @if(user_has_role(['super','admin','supportstaff']) && user_has_access(['viewbusmanager', 'editbusmanager', 'removebusmanager']))
                <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Bus Manager">
                    <a class="nav-link" href="/system/busmanager">
                      <i class="fa fa-fw fa-user"></i>
                      <span class="nav-link-text">Bus Manager</span>
                    </a>
                </li>
                @endif

                    @if(user_has_role(['super','admin','busmanager']) && user_has_access(['viewbuscounter', 'addbuscounter', 'editbuscounter', 'removebuscounter']))
                    <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Bus Counter">
                        <a class="nav-link" href="/system/buscounter">
                          <i class="fa fa-fw fa-university"></i>
                          <span class="nav-link-text">Bus Counter</span>
                      </a>
                    </li>
                    @endif

                    @if(user_has_role(['super','admin','busmanager']) && user_has_access(['viewcounterstaff', 'addcounterstaff', 'editcounterstaff', 'removecounterstaff']))
                    <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Counter Staff">
                        <a class="nav-link" href="/system/counterstaff">
                          <i class="fa fa-fw fa-users"></i>
                          <span class="nav-link-text">Counter Staff</span>
                        </a>
                    </li>
                    @endif

                {{--@if( user()->role == 'admin' || user()->role == 'supportstaff' || user()->role == 'busmanager' )--}}
                {{--<li class="nav-item" data-toggle="tooltip" data-placement="right" title="Counter Staff">--}}
                    {{--<a class="nav-link" href="/system/buses">--}}
                      {{--<i class="fa fa-fw fa-bus"></i>--}}
                      {{--<span class="nav-link-text">Manage Bus</span>--}}
                    {{--</a>--}}
                {{--</li>--}}
                {{--@endif--}}

                {{--@if( user()->role == 'admin' || user()->role == 'supportstaff' || user()->role == 'busmanager' )--}}
                {{--<li class="nav-item" data-toggle="tooltip" data-placement="right" title="Bus Schedule">--}}
                    {{--<a class="nav-link" href="/system/busschedule">--}}
                      {{--<i class="fa fa-fw fa-clock-o"></i>--}}
                      {{--<span class="nav-link-text">Bus Schedule</span>--}}
                    {{--</a>--}}
                {{--</li>--}}
                {{--@endif--}}

                {{--@if( user()->role == 'admin' || user()->role == 'supportstaff' || user()->role == 'busmanager' )--}}
                {{--<li class="nav-item" data-toggle="tooltip" data-placement="right" title="Tickets">--}}
                    {{--<a class="nav-link" href="/system/tickets">--}}
                      {{--<i class="fa fa-fw fa-ticket"></i>--}}
                      {{--<span class="nav-link-text">Tickets</span>--}}
                    {{--</a>--}}
                {{--</li>--}}
                {{--@endif--}}

                {{--@if( user()->role == 'admin' || user()->role == 'supportstaff' || user()->role == 'busmanager' )--}}
                {{--<li class="nav-item" data-toggle="tooltip" data-placement="right" title="Book Tickets">--}}
                    {{--<a class="nav-link" href="/system/booktickets">--}}
                      {{--<i class="fa fa-fw fa-keyboard-o"></i>--}}
                      {{--<span class="nav-link-text">Book Tickets</span>--}}
                    {{--</a>--}}
                {{--</li>--}}
                {{--@endif--}}

                {{--@if( user()->role == 'admin' || user()->role == 'supportstaff' || user()->role == 'busmanager' )--}}
                {{--<li class="nav-item" data-toggle="tooltip" data-placement="right" title="Transactions">--}}
                    {{--<a class="nav-link" href="/system/transaction">--}}
                      {{--<i class="fa fa-fw fa-money"></i>--}}
                      {{--<span class="nav-link-text">Transactions</span>--}}
                    {{--</a>--}}
                {{--</li>--}}
                {{--@endif--}}

                {{--@if(user()->role == 'admin' || user()->role == 'supportstaff' )--}}
                {{--<li class="nav-item" data-toggle="tooltip" data-placement="right" title="Support Tickets">--}}
                    {{--<a class="nav-link" href="/supportticket">--}}
                      {{--<i class="fa fa-fw fa-tags"></i>--}}
                      {{--<span class="nav-link-text">Support Tickets</span>--}}
                    {{--</a>--}}
                {{--</li>--}}
                {{--@endif--}}

                @if(user()->role->name == 'super')
                    <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Support Tickets">
                        <a class="nav-link" href="{{ route('managerole') }}">
                            <i class="fa fa-fw fa-tags"></i>
                            <span class="nav-link-text">Manage Roles</span>
                        </a>
                    </li>
                @endif

            @endif
          </ul>
          <ul class="navbar-nav sidenav-toggler">
            <li class="nav-item">
              <a class="nav-link text-center" id="sidenavToggler">
                <i class="fa fa-fw fa-angle-left"></i>
              </a>
            </li>
          </ul>
          <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="/system/profile">{{ user()->name }}</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-toggle="modal" data-target="#exampleModal">
                <i class="fa fa-fw fa-sign-out"></i>Logout</a>
            </li>
          </ul>
        </div>
      </nav>
<div class="content-wrapper">
    <div class="container-fluid">
        @if (session('status_success'))
            <div class="alert alert-success" role="alert">
                {{ session('status_success') }}
            </div>
        @endif

        @if (session('status_error'))
            <div class="alert alert-danger" role="alert">
                {{ session('status_error') }}
            </div>
        @endif
        @yield('body', '')
    </div>
    <!-- /.container-fluid-->
    <!-- /.content-wrapper-->
    <footer class="sticky-footer">
        <div class="container">
            <div class="text-center">
                <small>Copyright © BTRS 2020</small>
            </div>
        </div>
    </footer>
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fa fa-angle-up"></i>
    </a>
    <!-- Logout Modal-->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="{{ route('logout') }}">Logout</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap core JavaScript-->
</div>
<script src="{{ asset('/assets/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- Core plugin JavaScript-->
<script src="{{ asset('/assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
<!-- Custom scripts for all pages-->
<script src="{{ asset('/assets/js/sb-admin.min.js') }}"></script>
@yield('javascript', '')
</body>
</html>