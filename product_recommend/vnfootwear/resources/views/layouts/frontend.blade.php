<!doctype html>
<html>

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4"
    crossorigin="anonymous">
  <link rel="stylesheet" href="{{ asset('frontend/assests/css/style.css') }}">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

  <!-- Google font -->
  <link href="https://fonts.googleapis.com/css?family=Hind:400,700" rel="stylesheet">
  <!-- Slick -->
  <link type="text/css" rel="stylesheet" href="{{ asset('frontend/list/css/slick.css') }}" />
  <!-- Bootstrap -->
  <link type="text/css" rel="stylesheet" href="{{ asset('frontend/list/css/bootstrap.min.css') }}" />

  <link type="text/css" rel="stylesheet" href="{{ asset('frontend/list/css/slick-theme.css') }}" />
  <!-- nouislider -->
  <link type="text/css" rel="stylesheet" href="{{ asset('frontend/list/css/nouislider.min.css') }}" />
  <!-- Font Awesome Icon -->
  <link rel="stylesheet" href="{{ asset('frontend/list/css/font-awesome.min.css') }}">
  <!-- Custom stlylesheet -->
  <link type="text/css" rel="stylesheet" href="{{ asset('frontend/list/css/style.css') }}" />
  @yield('css')

  <title>VnFootwear</title>
</head>

<body>
  <nav style="position: fixed;height: 80px;" class="navbar fixed-top navbar-expand-lg navbar-dark">
    <div class="container-fluid">
      <div class="col-1">
        <a class="navbar-brand" href="{{ route('home') }}">
          <img style="position: absolute;top: -20px;left: -7px;" src="{{ asset('frontend/logo.png') }}" alt="">
        </a>
      </div>
      <div class="col-7">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item">
            <a class="nav-link" href="/products/list?genre=men">MEN</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/products/list?genre=women">WOMEN</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/products/list?genre=kids">KIDS</a>
          </li>
        </ul>
      </div>
      <div class="col-4">
        <form action="/products/list" method="GET" style="position: absolute;right: -6px;top: -10px;" class="form-inline my-2 my-lg-0">
          <input class="form-control mr-sm-2" name="name" type="search" placeholder="Search" aria-label="Search">
          <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
        </form>
      </div>
    </div>
    
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
      aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="top-nav">
        @if (Auth::check())
        <li style="position: absolute;right: 25px;top: -4px;width: 300px;list-style: none;"><a style="float:right;" href="#" id="user_dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{Auth::user()->name}}  <i class="fa fa-user-circle"></i></a>
          <div style="min-width: 75px;margin-left: 220px" class="dropdown-menu" aria-labelledby="user_dropdown">
            <button class="btn-sm dropdown-item" type="button"><a style="color: #212529;" href="{{route('logout')}}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></button>
          </div>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
          </form>
        </li>
        @else
        <a href="{{ route('register') }}">SIGN UP</a>
        <a href="{{ route('login') }}">LOG IN <span><i class="fa fa-user"></i></span></a>
        @endif
      <a href="{{ route('cart') }}">
        <span>
          <i class="fa fa-shopping-cart"></i>
        </span>
        <span id="total_products">
          @if(Session::has('products') && Session::get('total_products') > 0)
            {{ Session::get('total_products') }}
          @endif
        </span>
      </a>
    </div>
  </nav>
  
  @yield('content')

  <footer>
    <div class="sub-footer">
      <div class="container">
        <div class="row">
          <ul>
            <li>
              <span>
                <img src="{{ asset('frontend/assests/image/vie-flag-icon.png')}}">
              </span>Vie</li>
            <li>Privacy Policy</li>
            <li>Terms and Conditions</li>
            <li>© 2018 VnFootwear Inc.</li>
          </ul>
        </div>
      </div>
    </div>
  </footer>

  <!-- Optional JavaScript -->
  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
    crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ"
    crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm"
    crossorigin="anonymous"></script>

  <script src="{{ asset('frontend/list/js/jquery.min.js') }}"></script>
  <script src="{{ asset('frontend/list/js/bootstrap.min.js') }}"></script>
  <script src="{{ asset('frontend/list/js/slick.min.js') }}"></script>
  <script src="{{ asset('frontend/list/js/nouislider.min.js') }}"></script>
  <script src="{{ asset('frontend/list/js/jquery.zoom.min.js') }}"></script>
  <script src="{{ asset('frontend/list/js/main.js') }}"></script>
  @yield('script')
</body>

</html>