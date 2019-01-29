<!DOCTYPE html>
<!-- Honeypays Micro Credit Investment Limited
Developed by Altechtic Solutions | altechtic.com.ng | 08106813749

 -->
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>
    @yield('script')

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

   <!-- <link href="{{ asset('/css/vendor/datatables/dataTables.bootstrap4.css') }}" rel="stylesheet"> -->
   <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
   {{-- <script src="{{ asset('js/vue.js') }}"></script> --}}
   {{-- <script src="https://cdn.jsdelivr.net/npm/vue@2.5.16/dist/vue.min.js"></script>
    <script src="{{ asset('js/main.js') }}"></script> --}}
    <style type="text/css">
        body{
            background-color: blue;
            background: url(./bg/mcredit_bg.jpg) no-repeat center center fixed;
            background-size: cover;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
        }
        .card-header{
            color: blue;
        }
    </style>

   @yield('css')
   {{-- <link href="/public/vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet"> --}}
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <a href="/"><img src="{{ asset('public/honeylogo.jpg') }}"></a>
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li><a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a></li>
                           
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <strong>{{ Auth::user()->name }}</strong><span class="caret"></span>
                                </a>

                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">

                                @yield('menu')
                                @if (Auth::user()->role == 'admin')
                                <a href="/admin" class="dropdown-item">Dashboard</a>
                                    <a href="/admin/newcustomer" class="dropdown-item">Add Customer</a>
                                    <a href="/admin/transaction" class="dropdown-item">Add Transaction</a>
                                    <a href="/admin/search" class="dropdown-item">Search Customer</a>
                                    <a href="/admin/addstaff" class="dropdown-item">Add/Edit Staff</a>
                                    <a href="/admin/searchstaff" class="dropdown-item">Search Staff</a>
                                    <a href="/admin/addadmin" class="dropdown-item">Add Admin</a>
                                    <a href="/admin/addadmin2" class="dropdown-item">Add Admin Mini</a>
                                    <a href="/admin/changepass" class="dropdown-item">Change Password</a>

                                @endif

                                 @if (Auth::user()->role == 'staff')

                                 <a href="/staff" class="dropdown-item">Dashboard</a>
                                <a href="/staff/newcustomer" class="dropdown-item">Add Customer</a>
                                <a href="/staff/transaction" class="dropdown-item">Add Transaction</a>
                                <a href="/staff/search" class="dropdown-item">Search Customer</a>
                                <a href="/staff/changepass" class="dropdown-item">Change Password</a>

                                 @endif

                                 @if (Auth::user()->role == 'admin2')

                                 <a href="/admin2" class="dropdown-item">Dashboard</a>
                                <a href="/admin2/newcustomer" class="dropdown-item">Add Customer</a>
                                <a href="/admin2/transaction" class="dropdown-item">Add Transaction</a>
                                <a href="/admin2/search" class="dropdown-item">Search Customer</a>
                                <a href="/admin2/changepass" class="dropdown-item">Change Password</a>
                                <a href="/verify/add" class="dropdown-item">Add Verification</a>
                                 @endif

                                 @if (Auth::user()->role == 'admin' || Auth::user()->role == 'admin2')

                                 <a href="/verify/view" class="dropdown-item">View Verification</a>

                                 @endif

                                 @if (Auth::user()->role == 'customer')

                                 <a href="/customer" class="dropdown-item">Dashboard</a>
                                <a href="/customer/collateral" class="dropdown-item">Kin/Garantors</a>

                                 @endif

                                 @if (Auth::user()->role != 'customer')

                                 <a href="/referal" class="dropdown-item">Referal</a>

                                 @endif



                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                    
                                </div>
                            </li>
                            @if (Auth::user()->role == 'admin2' || Auth::user()->role == 'admin' )
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <strong>Statistics</strong><span class="caret"></span>
                                </a>

                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a href="/stats/day" class="dropdown-item">Today</a>
                                    <a href="/stats/week" class="dropdown-item">Week</a>
                                    <a href="/stats/month" class="dropdown-item">Month</a>
                                    <a href="/stats/all" class="dropdown-item">All time</a>
                                </div>
                            </li>
                            @endif

                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
    @yield('modal')
    <!-- Scripts -->
   {{--  <script src="{{ asset('js/main.js') }}"></script> --}}
   {{-- <script src="https://cdn.jsdelivr.net/npm/vue@2.5.13/dist/vue.js"></script> --}}
   {{-- <script src="{{ asset('js/vue.js') }}"></script> --}}
{{-- <script>
    var app = new Vue({
        el: '#app',
        data: {
            message: "test",
            name:"abu",
        },
        methods:{
            test(){
                alert(this.message);
            }
        }
    })
    </script> --}}
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('/css/vendor/datatables/sb-admin-datatables.min.js') }}"></script>
    <!-- <script src="{{ asset('/css/vendor/datatables/jquery.dataTables.js') }}"></script> -->
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('/css/vendor/datatables/dataTables.bootstrap4.js') }}"></script>
    @yield('js')
</body>
</html>
