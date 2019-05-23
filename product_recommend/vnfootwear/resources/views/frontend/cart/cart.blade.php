@extends('../layouts.frontend')

@section('css')
<style>
	footer{
		margin-top: 200px;
	}
</style>
<link rel="stylesheet" href="https://unpkg.com/element-ui/lib/theme-chalk/index.css">
@endsection

@section('content')
    <!-- BREADCRUMB -->
	<div style="margin-top:80px;" id="breadcrumb">
		<div class="container">
			<ul class="breadcrumb">
				<li><a href="{{ route('home') }}">Home</a></li>
				<li><a href="{{ route('products_list') }}">Products</a></li>
				<li class="active">Your cart</li>
			</ul>
		</div>
	</div>
	<!-- /BREADCRUMB -->
    <div style="min-height:274px;" id="app" class="container">
        <div class="main">
            <div class="container">
                @if(Session::has('products') && Session::get('total_products') > 0)
                <div class="row">
                    <div class="order-list">
                        <div class="header">
                            <h1>YOUR BAG 
                                <span>{{Session::get('total_products')}} ITEMS</span>
                            </h1>
                        </div>
                        <div class="body">
                            @foreach (Session::get('products') as $product)
                            <div class="item">
                                <div class="description">
                                    <div class="image">
                                        <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}">
                                    </div>
                                    <div class="inner-description">
                                        <h3 id="name">{{ $product['name'] }}</h3>
                                        <h3 id="code">{{ $product['code'] }}</h3>
                                        <h3 id="gender">Genre:
                                            <span>{{ $product['genre'] }}</span>
                                        </h3>
                                        <h3 style="position: relative;" id="color">Color:
                                            <span style="position:absolute;top:2px;left:223px;width:10px;height:10px;background:{{$product['color']}}"></span>
                                        </h3>
                                        <h3 id="size">Size:
                                            <span>{{$product['size']}}</span>
                                        </h3>
                                    </div>
                                </div>
                                <div class="price">
                                    <span id="original-price">${{$product['price']}}</span>
                                    <span>x</span>
                                    <span id="quantity">{{$product['quantity']}}</span>
                                    <span id="total-price">${{$product['price'] * $product['quantity']}}</span>
                                </div>
                                <div class="close">
                                    <i @click="delete_item(`{{$product['key']}}`)" class="fa fa-times"></i>
                                </div>
                            </div> 
                            @endforeach
                        </div>
                        <div class="check-out">
                            <a href="{{ route('checkout') }}"><button> CHECKOUT</button></a> 
                        </div>
                    </div>
                    <div class="order-summary">
                        <div class="pay">
                            <div class="header">
                                <h1>ORDER SUMMARY:
                                    <span>{{Session::get('total_products')}} ITEMS</span>
                                </h1>
                            </div>
                            <div class="main">
                                <div class="count-product">
                                    <h1>{{Session::get('total_products')}} products</h1>
                                    <div class="count-inner">
                                        <ul>
                                            <li>Original price
                                                <span id="original-price">${{Session::get('total_price')}}</span>
                                            </li>
                                            <li>Promo
                                                <span id="promo-price">-${{Session::get('total_price') * Session::get('discount') / 100}}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="total">
                                    <p>Total
                                        <span id="total_price">${{Session::get('total_price') * (1 - (Session::get('discount') / 100))}}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="promo">
                            <input type="text" v-model="promo_code" id="promo" placeholder="promo code">
                            <button @click="get_promo">APPLY</button>
                        </div>
                    </div>
                </div>
                @else
                Your bag is empty
                @endif
            </div>
        </div>
    </div>

@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.18.0/axios.min.js"></script>
<script src="https://unpkg.com/vue/dist/vue.js"></script>
<script src="https://unpkg.com/element-ui/lib/index.js"></script>
<script>
    var app = new Vue({
		el: '#app',
        created: function() {
			this.init();
		},
		data: {
			promo_code: '',
            promo_id: '',
            discount: null
		},
		methods: {
            init() {
				var footer = document.querySelector("footer");
				footer.style.marginTop = "383px";
			},
			delete_item(key) {
                var com = this;
				axios.get(`/shopping-cart/remove`, {params: {key: key}})
				.then(function (response) {
					location.reload();
				})
				.catch(function (error) {
					console.log(error);
				});
            },
			get_promo() {
				if (this.promo_code.trim() === '') {
					return this.$notify({
						title: 'Warning',
						message: 'Please enter promotion code.',
						type: 'warning'
				    });
				}
				var com = this;
				axios.get(`/api/promotions`, {params: {code: com.promo_code.trim()}})
				.then(function (response) {
                    if (response.data[0].length != 0) {
                        com.discount = response.data[0][0].discount;
                        com.promo_id = response.data[0][0].id;
                        com.$notify({
                            title: 'Success',
                            message: 'Successful use promotion code.',
                            type: 'success'
                        });
                        return axios.get(`/shopping-cart/use-promotion`, {params: {promo: com.discount, promo_id: com.promo_id}})
                    } else {
                        return com.$notify({
                            title: 'Error',
                            message: 'Invalid promotion code.',
                            type: 'error'
                        });
                    }
				})
                .then(function (response) {
                    location.reload();
                })
				.catch(function (error) {
					return com.$notify({
						title: 'Error',
						message: 'Invalid promotion code.',
						type: 'error'
					});
				});

                
			}
		}
	})
</script>
@endsection