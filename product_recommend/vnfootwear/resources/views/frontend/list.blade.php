@extends('../layouts.frontend')

@section('css')
<style>
	a:hover{
		cursor: pointer;
	}
</style>

@endsection

@section('content')
	<div id="app">
		<!-- BREADCRUMB -->
		<div style="margin-top:80px;" id="breadcrumb">
			<div class="container">
				<ul class="breadcrumb">
					<li><a href="{{ route('home') }}">Home</a></li>
					<li class="active">Products</li>
				</ul>
			</div>
		</div>
		<!-- /BREADCRUMB -->

		<!-- section -->
		<div class="section">
			<!-- container -->
			<div class="container">
				<!-- row -->
				<div class="row">
					<!-- ASIDE -->
					<div id="aside" class="col-md-3">
						<!-- aside widget -->
						<div v-if="category != '' || genre != ''" class="aside">
							<h3 class="aside-title">Shop by:</h3>
							<ul v-if="category != ''" class="filter-list">
								<li><span class="text-uppercase">Category:</span></li>
								<li><a @click="category=''">@{{category}}</a></li>
							</ul>

							<ul v-if="genre != ''" class="filter-list">
								<li><span class="text-uppercase">Gender:</span></li>
								<li><a @click="genre=''">@{{genre}}</a></li>
							</ul>

							<button class="primary-btn" @click="clear">Clear All</button>
						</div>
						<!-- /aside widget -->

						<!-- aside widget -->
						<div class="aside">
							<h3 class="aside-title">Filter by Category</h3>
							<ul class="list-links">
								<li :class="{active: category === 'originals'}"><a @click="category='originals'">Originals</a></li>
								<li :class="{active: category === 'football'}"><a @click="category='football'">Football</a></li>
								<li :class="{active: category === 'training'}"><a @click="category='training'">Training</a></li>
								<li :class="{active: category === 'running'}"><a @click="category='running'">Running</a></li>
							</ul>
						</div>
						<!-- /aside widget -->

						<!-- aside widget -->
						<div class="aside">
							<h3 class="aside-title">Filter by Gender</h3>
							<ul class="list-links">
								<li :class="{active: genre === 'men'}"><a @click="genre='men'">Men</a></li>
								<li :class="{active: genre === 'women'}"><a @click="genre='women'">Women</a></li>
								<li :class="{active: genre === 'kids'}"><a @click="genre='kids'">Kids</a></li>
							</ul>
						</div>
						<!-- /aside widget -->

					</div>
					<!-- /ASIDE -->

					<!-- MAIN -->
					<div v-if="products.length != 0" id="main" class="col-md-9">
						<!-- store top filter -->
						<div class="store-filter clearfix">
							<div class="pull-right">
								<div class="page-filter">
									<span class="text-uppercase">Show:</span>
									<select v-model="page_size" class="input">
										<option value="5">5</option>
										<option value="10">10</option>
										<option value="15">15</option>
									</select>
								</div>
								<ul class="store-pages">
									<li><span class="text-uppercase">Page:</span></li>
									<li v-for="page_num in total_page" :class="{active: current_page === page_num}"><a @click="page_change(page_num)">@{{page_num}}</a></li>
		                            <li><a @click="go_next"><i class="fa fa-caret-right"></i></a></li>
								</ul>
							</div>
						</div>
						<!-- /store top filter -->

						<!-- STORE -->
						<div id="store">
							<!-- row -->
							<div class="row">
								<!-- Product Single -->
								<div v-for="product in products" :key="product.id" class="col-md-4 col-sm-6 col-xs-6">
									<div class="product product-single">
										<div class="product-thumb">
											<button @click="viewProduct(product.view_url)" class="main-btn quick-view"><i class="fa fa-search-plus"></i> Quick view</button>
											<img :src="product.thumbnail" alt="product.name">
										</div>
										<div class="product-body">
											<h3 class="product-price">$@{{product.price}}</h3>
											<h2 class="product-name"><a :href="product.view_url">@{{product.name}}</a></h2>
										</div>
									</div>
								</div>
								<!-- /Product Single -->
							</div>
							<!-- /row -->
							@auth
							<h1 style="margin: 15px; border-bottom: 1px solid black">Recommend Products</h1>
							<!-- Recommend row -->
							<div class="row">
								<!-- Product Single -->
								@foreach ($recs as $rec)
								<div class="col-md-4 col-sm-6 col-xs-6">
									<div class="product product-single">
										<div class="product-thumb">
											<a href="{{$rec->view_url}}" class="btn main-btn quick-view" role="button"><i class="fa fa-search-plus"></i> Quick view</a>
											<img src="{{$rec->thumbnail}}" alt="{{$rec->name}}">
										</div>
										<div class="product-body">
											<h3 class="product-price">${{$rec->price}}</h3>
											<h2 class="product-name"><a href="{{$rec->view_url}}">{{$rec->name}}</a></h2>
										</div>
									</div>
								</div>
								<!-- <div v-for="product in products" :key="product.id" class="col-md-4 col-sm-6 col-xs-6">
									<div class="product product-single">
										<div class="product-thumb">
											<button @click="viewProduct(product.view_url)" class="main-btn quick-view"><i class="fa fa-search-plus"></i> Quick view</button>
											<img :src="product.thumbnail" alt="product.name">
										</div>
										<div class="product-body">
											<h3 class="product-price">$@{{product.price}}</h3>
											<h2 class="product-name"><a :href="product.view_url">@{{product.name}}</a></h2>
										</div>
									</div>
								</div> -->
								@endforeach
								<!-- /Product Single -->
							</div>
							<!-- /Recommend row -->
							@endauth
						</div>
						<!-- /STORE -->

						<!-- store bottom filter -->
						<div class="store-filter clearfix">
							<div class="pull-right">
								<div class="page-filter">
									<span class="text-uppercase">Show:</span>
									<select v-model="page_size" class="input">
										<option value="5">5</option>
										<option value="10">10</option>
										<option value="15">15</option>
									</select>
								</div>
								<ul class="store-pages">
									<li><span class="text-uppercase">Page:</span></li>
									<li v-for="page_num in total_page" :class="{active: current_page === page_num}"><a @click="page_change(page_num)">@{{page_num}}</a></li>
		                            <li><a @click="go_next"><i class="fa fa-caret-right"></i></a></li>
								</ul>
							</div>
						</div>
						<!-- /store bottom filter -->
					</div>
					<div v-else id="main" class="col-md-9">
						No product available
					</div>
					<!-- /MAIN -->
				</div>
				<!-- /row -->
			</div>
			<!-- /container -->
		</div>
		<!-- /section -->
	</div>
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.18.0/axios.min.js"></script>
<script src="https://unpkg.com/vue/dist/vue.js"></script>
<script>
    var app = new Vue({
      el: '#app',
      mounted: function() {
        this.init();
      },
      data: function() {
        return {
            products: [],
            current_page: 1,
            page_size: 5,
            total: null,
            name: `{{$name}}`,
            category: `{{$category}}`,
            genre: `{{$genre}}`
        }
      },
	  watch: {
		page_size: function (val) {
			this.init();
		},
		category: function (val) {
			this.init();
		},
		genre: function (val) {
			this.init();
		}
	  },
	  computed: {
		total_page: function(){return Math.ceil(this.total/this.page_size)}
	  },
      methods: {
        handleSizeChange(val) {
            this.page_size = val;
            this.init();
        },
        init() {
            var com = this;
            axios.get('/api/products', {params: {
                limit: com.page_size, 
                offset: com.page_size * (com.current_page - 1), 
                name: com.name,
                category: com.category,
                genre: com.genre
            }})
            .then(function (response) {
                com.products = response.data[0];
                com.total = response.data[1];
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        viewProduct(address) {
            window.location.href = address;
        },
        clear() {
            this.current_page = 1;
            this.page_size = 5;
            this.name = '';
            this.category = '';
            this.genre = '';
            this.init();
        },
		page_change(page) {
			this.current_page = page;
			this.init();
		},
		go_next() {
			var com = this;
			var next = com.current_page + 1;
			if (next <= com.total_page) {
				com.current_page = next;
				com.init();
			}
		},
      }
    })
</script>
<script>
String.prototype.capitalize = function() {
    return this.replace(/(?:^|\s)\S/g, function(a) { return a.toUpperCase(); });
};
</script>
@endsection