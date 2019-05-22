
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>VnFootwear Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" href="{{ asset('apple-icon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>

    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/flag-icon.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/scss/style.css') }}">
    @yield('css')
    <style>
        a.nav-link:hover {
            background: silver;
        }
    </style>

</head>
<body>
    <!-- Left Panel -->

    <aside id="left-panel" class="left-panel">
        <nav class="navbar navbar-expand-sm navbar-default">

            <div class="navbar-header">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-menu" aria-controls="main-menu" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fa fa-bars"></i>
                </button>
                <a class="navbar-brand" href="{{ route('admin.dashboard') }}"><img src="{{ asset('images/logo.png') }}" alt="Logo"></a>
                <a class="navbar-brand hidden" href="{{ route('admin.dashboard') }}"><img src="{{ asset('images/logo2.png') }}" alt="Logo"></a>
            </div>

            <div id="main-menu" class="main-menu">
                <ul class="nav navbar-nav">
                    <li class="active">
                        <a href="{{ route('admin.dashboard') }}"> <i class="menu-icon fa fa-dashboard"></i>Dashboard </a>
                    </li>
                    <h3 class="menu-title">VnFootwear</h3><!-- /.menu-title -->
                    <li class="menu-item-has-children">
                        <a href="{{ route('admin.user') }}"> <i class="menu-icon fa fa-users"></i>User</a>
                    </li>
                    <li class="menu-item-has-children">
                        <a href="{{ route('admin.product') }}"> <i class="menu-icon fa fa-file-text"></i>Product</a>
                    </li>
                    <li class="menu-item-has-children">
                        <a href="{{ route('admin.promotion') }}"> <i class="menu-icon fa fa-picture-o"></i>Promotion</a>
                    </li>
                    <li class="menu-item-has-children">
                        <a href="{{ route('admin.order') }}"> <i class="menu-icon fa fa-shopping-cart"></i>Order</a>
                    </li>
                </ul>
            </div><!-- /.navbar-collapse -->
        </nav>
    </aside><!-- /#left-panel -->

    <!-- Left Panel -->

    <!-- Right Panel -->

    <div id="right-panel" class="right-panel">

        <!-- Header-->
        <header id="header" class="header">

            <div class="header-menu">

                <div class="col-sm-7">
                    <a id="menuToggle" class="menutoggle pull-left"><i class="fa fa fa-tasks"></i></a>
                </div>

                <div class="col-sm-5">
                    @if (Auth::check())
                        <div class="user-area dropdown float-right">
                            <a style="width: 300px;margin-right: 10px;" class="name">{{Auth::user()->name}}</a>
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img class="user-avatar rounded-circle" src="{{ asset('images/user/no-avatar-user.png') }}" alt="{{Auth::user()->name}}">
                            </a>

                            <div class="user-menu dropdown-menu">
                                    <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fa fa-power -off"></i>Logout
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        </header><!-- /header -->
        <!-- Header-->

        @yield('content')

    </div><!-- /#right-panel -->

    <!-- Right Panel -->

    
    @yield('script')

</body>
</html>
