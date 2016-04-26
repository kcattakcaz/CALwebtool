<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>CAL Awards</title>

    <!-- Fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
    <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700" rel='stylesheet' type='text/css'>

    <!-- Styles -->
    <!--<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet"> -->
    <!--<link href="{{secure_asset('css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{secure_asset('css/bootstrap-theme.min.css')}}" rel="stylesheet"> -->
    <link href="{{secure_asset('css/flatly_bootstrap.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{secure_asset("css/selectize.css")}}">
    <link rel="stylesheet" href="{{secure_asset("css/selectize.bootstrap3.css")}}">



    <style>
        body {
            font-family: 'Lato';
        }

        .fa-btn {
            margin-right: 6px;
        }
    </style>

    <!-- JavaScripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    {{-- <link href="{{secure_asset('js/bootstrap.min.js')}}"> --}}
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="{{secure_asset("js/selectize.min.js")}}"></script>

</head>
<body id="app-layout">

@if (Session::has('flash_notification.message'))
    @include('flash::message')
@endif



    <nav class="navbar navbar-default">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/home') }}">
                    CAL Awards
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    <li><a href="{{ url('/home') }}">My Dashboard</a></li>
                    <li><a href="{{action('FormDefinitionController@index')}}">Forms</a></li>
                    <li><a href="{{action('SubmissionController@index')}}">Submissions</a></li>
                    @if(Auth::check() && Auth::user()->isSystemAdmin() || (Auth::check() && Auth::user()->adminGroups()->count() > 0))
                        <li><a href="{{url('/settings')}}">Settings</a></li>
                    @else
                        <li><a href="{{action('UserController@show',['user'=>Auth::user()])}}">Profile</a></li>
                    @endif
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li><a href="{{ url('/login') }}">Login</a></li>
                        <li><a href="{{ url('/register') }}">Register</a></li>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')


    <script>
        $(document).ready(function() {
            $('#flash-overlay-modal').modal();
        });
    </script>
</body>
</html>
