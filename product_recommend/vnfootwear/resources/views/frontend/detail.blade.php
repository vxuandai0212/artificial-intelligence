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
<div id="app">
    <!-- BREADCRUMB -->
	<div style="margin-top:80px;" id="breadcrumb">
		<div class="container">
			<ul class="breadcrumb">
				<li><a href="{{ route('home') }}">Home</a></li>
				<li><a href="{{ route('products_list') }}">Products</a></li>
				<li class="active">@{{product.name}}</li>
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
				<!--  Product Details -->
				<div class="product product-details clearfix" style="width: 100%;">
					<div class="col-md-6">
						<div id="product-main-view" style="height: 300px;overflow: hidden;">
							<div class="product-view">
								<img style="position: relative; top: 25px;" :src="product.big_thumbnail" alt="product.name">
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="product-body">
							<h2 class="product-name">@{{product.name}}</h2>
							<h3 class="product-price">$@{{product.price}}</h3>
							<p><strong>Availability:</strong> In Stock</p>
							<div class="product-options">
								<ul class="size-option">
									<li><span class="text-uppercase">Size:</span></li>
									<li v-for="size in sizes" :class="{active: order.size === size}"><a @click="choose_size(size)">@{{size}}</a></li>
								</ul>
								<ul class="color-option">
									<li><span class="text-uppercase">Color:</span></li>
									<li v-for="color in colors" :class="{active: order.color === color}"><a @click="choose_color(color)" :style="{backgroundColor: color}"></a></li>
								</ul>
							</div>

							<div class="product-btns">
								<div class="qty-input">
									<span class="text-uppercase">QTY: </span>
									<input class="input" v-model="order.quantity" type="number">
								</div>
								<button @click="addToCart" class="primary-btn add-to-cart"><i class="fa fa-shopping-cart"></i> Add to Cart</button>
							</div>
						</div>
					</div>
				</div>
				<!-- /Product Details -->
			</div>
			<!-- /row -->
		</div>
		<!-- /container -->
	</div>
	<!-- /section -->

    <!-- Modal -->
    <div class="modal fade" id="order_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="false">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">SUCCESSFULLY ADDED TO BAG</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="cancel" aria-hidden="true">Continue Shopping  &times;</span>
                    </button>
                </div>
                <div class="modal-body">
                        <div class="container-fluid">
                          <div class="row">
                                <div class="col-4">
                                        <img :src="product.big_image">
                                    </div>
                                    <div class="col-4">
                                        <p id="name">@{{product.name}}</p>
                                        <p id="price">$@{{product.price}}</p>
                                        <p>Colour: <a :style="{backgroundColor: order.color}"></a></p>
                                        <p id="modal_size">Size: @{{order.size}}</p>
                                        <p id="modal_quantity">Quantity: @{{order.quantity}}</p>
                                    </div>
                                    <div class="col-4">
                                        <p id="total_items">@{{order.quantity}} ITEMS</p>
                                        <p>Total Product Cost:      
                                            <span class="price" id="total_price">$@{{order.quantity * product.price}}</span>
                                        </p>
                                        <a href="{{ route('cart') }}"><button id="viewbag">VIEW BAG</button></a>
                                        <a href="{{ route('checkout') }}"><button id="checkout">CHECKOUT</button></a>
                                    </div>
                          </div>
                        </div>
                      </div>
            </div>
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
			id: `{{$id}}`,
			product: {},
			order: {
				size: '',
				color: '',
				quantity: 1
			}
		},
		computed: {
			sizes: function(){return this.product.size.split(',');},
			colors: function(){return this.product.color.split(',');}
		},
		methods: {
			init() {
				var com = this;
				axios.get(`/api/products/${com.id}`)
				.then(function (response) {
					com.product = response.data;
				})
				.catch(function (error) {
					console.log(error);
				});
				var footer = document.querySelector("footer");
				footer.style.marginTop = "388px";
			},
			choose_color(color) {
				this.order.color = color;
			},
			choose_size(size) {
				this.order.size = size;
			},
			addToCart() {
				if (this.order.size === '' ||
                this.order.color === '' ||
                this.order.quantity < 1
				) {
					return this.$notify({
						title: 'Warning',
						message: 'Please complete order detail.',
						type: 'warning'
					});
				} else {
					$('#order_modal').modal('show')
				}
				var com = this;
				axios.get(`/shopping-cart/add`, {params: {product: com.product, order: com.order}})
				.then(function (response) {
					$('#total_products').text(response.data);
					return com.$notify({
						title: 'Success',
						message: 'Successful add item to cart.',
						type: 'success'
					});
				})
				.catch(function (error) {
					console.log(error);
				});
			}
		}
	})
</script>
<script>
        
</script>
@endsection