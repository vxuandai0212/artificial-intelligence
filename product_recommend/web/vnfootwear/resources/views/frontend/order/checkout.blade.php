@extends('../layouts.frontend')

@section('css')
<style>
body {
    height: initial!important;
}
footer {
    bottom: 0;
    position: absolute;
    width: 100%;
}
</style>
<link rel="stylesheet" href="https://unpkg.com/element-ui/lib/theme-chalk/index.css">
<!-- login -->
<link rel="stylesheet" type="text/css" href="{{ asset('frontend/login_v4/vendor/bootstrap/css/bootstrap.min.css') }}">
	
<link rel="stylesheet" type="text/css" href="{{ asset('frontend/login_v4/fonts/font-awesome-4.7.0/css/font-awesome.min.css') }}">

<link rel="stylesheet" type="text/css" href="{{ asset('frontend/login_v4/fonts/iconic/css/material-design-iconic-font.min.css') }}">

<link rel="stylesheet" type="text/css" href="{{ asset('frontend/login_v4/vendor/animate/animate.css') }}">
	
<link rel="stylesheet" type="text/css" href="{{ asset('frontend/login_v4/vendor/css-hamburgers/hamburgers.min.css') }}">

<link rel="stylesheet" type="text/css" href="{{ asset('frontend/login_v4/vendor/animsition/css/animsition.min.css') }}">

<link rel="stylesheet" type="text/css" href="{{ asset('frontend/login_v4/vendor/select2/select2.min.css') }}">
	
<link rel="stylesheet" type="text/css" href="{{ asset('frontend/login_v4/vendor/daterangepicker/daterangepicker.css') }}">

<link rel="stylesheet" type="text/css" href="{{ asset('frontend/login_v4/css/util.css') }}">

<link rel="stylesheet" type="text/css" href="{{ asset('frontend/login_v4/css/main.css') }}">
@endsection

@section('content')
<!-- BREADCRUMB -->
<div style="margin-top:80px;" id="breadcrumb">
    <div class="container">
        <ul class="breadcrumb">
            <li><a href="{{route('home')}}">Home</a></li>
            <li style="margin-top: 4px;" class="active">Checkout</li>
        </ul>
    </div>
</div>
@if(Session::has('products') && Session::get('total_products') > 0)
<div id="app" class="pay-form">
    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div style="margin-top: 170px;" class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="limiter">
                    <div class="container-login100">
                        <div class="wrap-login100 p-l-55 p-r-55 p-t-40 p-b-30">
                            <form class="login100-form validate-form" onSubmit="event.preventDefault();">
                                <span class="login100-form-title p-b-49">
                                    Login Form
                                </span>

                                <div class="wrap-input100 validate-input m-b-23" data-validate = "Username is reauired">
                                    <span class="label-input100">Username</span>
                                    <input v-model="login_form.username" class="input100" type="text" name="username" placeholder="Enter username">
                                    <span class="focus-input100" data-symbol="&#xf206;"></span>
                                </div>

                                <div class="wrap-input100 validate-input" data-validate="Password is required">
                                    <span class="label-input100">Password</span>
                                    <input v-model="login_form.password" class="input100" type="password" name="pass" placeholder="Enter password">
                                    <span class="focus-input100" data-symbol="&#xf190;"></span>
                                </div>
                                
                                <div class="text-right p-t-8 p-b-31">
                                    
                                </div>
                                
                                <div class="container-login100-form-btn">
                                    <div class="wrap-login100-form-btn">
                                        <div class="login100-form-bgbtn"></div>
                                        <button @click="login" class="login100-form-btn">
                                            Login
                                        </button>
                                    </div>
                                </div>

                                <div class="flex-col-c p-t-55">
                                    <a href="{{route('register')}}" class="txt2">
                                        Register
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-6">
                <form onSubmit="event.preventDefault();">
                    <div class="form-group">
                        <label for="name">NAME</label>
                        <input type="text" class="form-control" id="name" v-model="name" placeholder="Enter Your Full Name">
                    </div>
                    <div class="form-group">
                        <label for="address">ADDRESS</label>
                        <input type="text" class="form-control" v-model="address" id="address" placeholder="Enter Your Address">
                    </div>
                    <div class="form-group">
                        <label for="phone">PHONE NUMBER</label>
                        <input type="text" class="form-control" v-model="phone" id="phone" placeholder="Enter Your Phone Number">
                    </div>
                    <button @click="pay" class="btn btn-primary my-5">PAY</button>
                </form>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="total_money">TOTAL MONEY ($)</label>
                    <input value={{Session::get('total_price')}} disabled type="text" class="form-control" id="total_money">
                </div>
                <div class="form-group">
                    <label for="discount_money">DISCOUNT ($)</label>
                    <input value={{Session::get('total_price') * Session::get('discount') / 100}} disabled type="text" class="form-control" id="discount_money">
                </div>
                <div class="form-group">
                    <label for="pay_money">HAVE TO PAY ($)</label>
                    <input value={{Session::get('total_price') * (1 - (Session::get('discount') / 100))}} disabled type="text" class="form-control" id="pay_money">
                </div>
            </div>
        </div>
    </div>
    
</div>

@else
<div class="container">
    <div class="row">
        <div class="col-6">
            <p class="pt-5">Nothing to checkout</p>
        </div>
    </div>
</div>
@endif

@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.18.0/axios.min.js"></script>
<script src="https://unpkg.com/vue/dist/vue.js"></script>
<script src="https://unpkg.com/element-ui/lib/index.js"></script>
<script>
    var app = new Vue({
		el: '#app',
		data: {
            name: '',
            address: '',
            phone: '',
            login_form: {
                username: '',
                password: ''
            },
		},
		methods: {
			pay() {
                if (this.name.trim() === '' ||
                this.address.trim() === '' ||
                this.phone.trim() === ''
				) {
					return this.$notify({
						title: 'Warning',
						message: 'Please complete checkout detail.',
						type: 'warning'
					});
				}
            
                var com = this;
                axios.post('/api/orders', {name: com.name, address: com.address, phone: com.phone})
                .then(function (response) {
                    com.$notify({
						title: 'Success',
						message: 'Success. Thanks for your ordering.',
						type: 'success'
					});
                    setTimeout(function() {
                        window.location.href = "https://www.nganluong.vn/button_payment.php?receiver=vxuandai@gmail.com&product_name=" + Date.now() + Math.random() + "&price={{Session::get('total_price') * 23255}}&return_url={{route('success')}}&comments=Order shoes"; 
                    }, 1500)
                })
                .catch(function (error) {
                    if (error.response.status === 401) {
                        $('#myModal').modal('show');
                    }
                    if (error.response.status === 403) {
                        com.$notify.error({
                            title: 'Error',
                            message: error.response.data.message
                        });
                    }
                    console.log(error);
                });
            },
            login() {
                if (this.login_form.username.trim() === '' &&
                    this.login_form.password.trim() === ''
                ) {
                    return this.$notify({
                        title: 'Warning',
                        message: 'Please type username and password.',
                        type: 'warning'
                    });
                }
                var com = this;
                axios.post('/login', {
                    username: com.login_form.username, 
                    password: com.login_form.password})
                .then(function (response) {
                    location.reload()
                })
                .catch(function (error) {
                    return com.$notify({
                        title: 'Warning',
                        message: 'Account does not exist.',
                        type: 'warning'
                    });
                    console.log(error);
                });
            },
		}
	})
</script>
@endsection