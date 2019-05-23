@extends('../layouts.frontend')

@section('content')

    <div class="slide-show">
        <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
            <img class="d-block w-100" src="{{ asset('frontend/assests/image/banner1.jpg') }}" alt="First slide">
            </div>
            <div class="carousel-item">
            <img class="d-block w-100" src="{{ asset('frontend/assests/image/banner2.jpg') }}" alt="Second slide">
            </div>
            <div class="carousel-item">
            <img class="d-block w-100" src="{{ asset('frontend/assests/image/banner3.jpg') }}" alt="Third slide">
            </div>
        </div>
        <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
        </div>
    </div>

    <div class="container">
        <div class="trending-product">
            <h1>WHAT'S TRENDING</h1>
            <div class="card-deck collection">
                <div class="card">
                    <img class="card-img-top" src="{{ asset('frontend/category/football.jpg') }}" alt="Football Category">
                    <div class="card-body">
                        <h5 class="card-title">FOOTBALL</h5>
                        <div class="card-link"><a href="/products/list?category=football">SHOP NOW</a></div>
                    </div>
                </div>
                <div class="card">
                    <img class="card-img-top" src="{{ asset('frontend/category/original.jpg') }}" alt="Original Category">
                    <div class="card-body">
                        <h5 class="card-title">ORIGINALS</h5>
                        <div class="card-link"><a href="/products/list?category=originals">SHOP NOW</a></div>                 
                    </div>
                </div>
            </div>
            <div class="card-deck collection">
                <div class="card">
                    <img class="card-img-top" src="{{ asset('frontend/category/running.jpg') }}" alt="Running Category">
                    <div class="card-body">
                        <h5 class="card-title">RUNNING</h5>
                        <div class="card-link"><a href="/products/list?category=running">SHOP NOW</a></div>
                    </div>
                </div>
                <div class="card">
                    <img class="card-img-top" src="{{ asset('frontend/category/training.jpg') }}" alt="Training Category">
                    <div class="card-body">
                        <h5 class="card-title">TRAINING</h5>
                        <div class="card-link"><a href="/products/list?category=training">SHOP NOW</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection