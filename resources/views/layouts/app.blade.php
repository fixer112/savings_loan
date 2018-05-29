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
    <style type="text/css">
        body{
            background-color: blue;
        }
        .card-header{
            color: blue;
        }
    </style>

   @yield('css')
   <link href="/public/vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <a href="/"><img src="{{ asset('honeylogo.jpg') }}"></a>
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

                                 @endif

                                 @if (Auth::user()->role == 'customer')

                                 <a href="/customer" class="dropdown-item">Dashboard</a>
                                <a href="/customer/collateral" class="dropdown-item">Kin/Garantors</a>

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

                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="/public/vendor/datatables/sb-admin-datatables.min.js"></script>
    <script src="/public/vendor/datatables/jquery.dataTables.js"></script>
    <script src="/public/vendor/datatables/dataTables.bootstrap4.js"></script>
    @yield('js')
</body>
</html>
