@extends('../layouts.master')

@section('css')
<link rel="stylesheet" href="https://unpkg.com/element-ui/lib/theme-chalk/index.css">
@endsection

@section('content')
        <div class="breadcrumbs">
            <div class="page-header float-left">
                <div class="page-title">
                    <h1>Manage Orders</h1>
                </div>
            </div>
        </div>

        <div class="content mt-3" id="app">
            <div class="animated fadeIn">
                <div class="card">
                    <div class="card-body">
                        <el-form :inline="true" :model="form_search" class="demo-form-inline">
                            <el-form-item label="Customer Name">
                                <el-input v-model="form_search.name" placeholder="Customer name"></el-input>
                            </el-form-item>
                            <el-form-item label="Phone">
                                <el-input v-model="form_search.phone" placeholder="Phone"></el-input>
                            </el-form-item>
                            <el-form-item label="Status">
                                <el-select v-model="form_search.status" placeholder="Select order status">
                                    <el-option
                                        v-for="item in statuses"
                                        :key="item.value"
                                        :label="item.label"
                                        :value="item.value">
                                    </el-option>
                                </el-select>
                            </el-form-item>
                            <el-form-item>
                                <el-button @click="search" type="primary">Search</el-button>
                                <el-button @click="clear" type="default">Clear</el-button>
                            </el-form-item>
                        </el-form>
                        <hr>
                        <el-table :data="orders" stripe border style="width: 100%">
                            <el-table-column type="expand">
                                <template slot-scope="props">
                                    <el-table :data="props.row.order_details" stripe border style="width: 100%">
                                        <el-table-column label="Image" width="70">
                                            <template slot-scope="scope">
                                                <img alt="scope.row.product.name" :src="scope.row.product.images">
                                            </template>
                                        </el-table-column>
                                        <el-table-column prop="product.code" label="Code"></el-table-column>
                                        <el-table-column prop="product.name" label="Name"></el-table-column>
                                        <el-table-column prop="size" label="Size"></el-table-column>
                                        <el-table-column prop="quantity" label="Quantity"></el-table-column>
                                        <el-table-column prop="color" label="Color">
                                            <template slot-scope="scope">
                                                <div :style="{background: scope.row.color, height: '45px'}"></div>
                                            </template>
                                        </el-table-column>
                                    </el-table>
                                </template>
                            </el-table-column>
                            <el-table-column type="index" :index="indexMethod" width="50" align="center"></el-table-column>
                            <el-table-column prop="name" label="Name"></el-table-column>
                            <el-table-column prop="phone" label="Phone"></el-table-column>
                            <el-table-column prop="address" label="Address"></el-table-column>
                            <el-table-column prop="total_quantity" width="120" label="Total products"></el-table-column>
                            <el-table-column prop="promo.code" width="130" label="Promotion code"></el-table-column>
                            <el-table-column prop="original_price" label="Original price">
                                <template slot-scope="scope">
                                    $@{{scope.row.original_price}}
                                </template>
                            </el-table-column>
                            <el-table-column prop="total_price" label="Total price">
                                <template slot-scope="scope">
                                    $@{{scope.row.total_price}}
                                </template>
                            </el-table-column>
                            <el-table-column prop="status" label="Status">
                                <template slot-scope="scope">
                                    <el-select @change="edit_status(scope.row)" v-model="scope.row.status" placeholder="Select order status">
                                        <el-option
                                            v-for="item in statuses_change"
                                            :key="item.value"
                                            :label="item.label"
                                            :value="item.value">
                                        </el-option>
                                    </el-select>
                                </template>
                            </el-table-column>
                        </el-table>
                        <hr>
                        <el-pagination
                            @size-change="handleSizeChange"
                            @current-change="handleCurrentChange"
                            :page-sizes="[10, 15, 20, 100]"
                            :page-size="page_size"
                            layout="total, sizes, prev, pager, next"
                            :total="total">
                        </el-pagination>
                    </div>
                </div>
            </div><!-- .animated -->
        </div>

@endsection

@section('script')
<script src="{{ asset('assets/js/vendor/jquery-2.1.4.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"></script>
<script src="{{ asset('assets/js/plugins.js') }}"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.18.0/axios.min.js"></script>

<script src="https://unpkg.com/vue/dist/vue.js"></script>
<script src="https://unpkg.com/element-ui/lib/index.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/element-ui/2.4.7/locale/en.js"></script>
<script>
    ELEMENT.locale(ELEMENT.lang.en)
</script>
<script>
    var app = new Vue({
      el: '#app',
      mounted: function() {
        this.table_change();
      },
      data: function() {
        return {
            statuses: [
                {'label': 'New', 'value': 1},
                {'label': 'Shipping', 'value': 2},
                {'label': 'Received', 'value': 3},
                {'label': 'Refund', 'value': 4},
                {'label': 'Cancelled', 'value': 5},
                {'label': 'All', 'value': ''}
            ],
            statuses_change: [
                {'label': 'New', 'value': 1},
                {'label': 'Shipping', 'value': 2},
                {'label': 'Received', 'value': 3},
                {'label': 'Refund', 'value': 4},
                {'label': 'Cancelled', 'value': 5}
            ],
            orders: [],
            current_page: 1,
            page_size: 10,
            total: null,
            form_search: {
                name: '',
                phone: '',
                status: 1
            },
        }
      },
      methods: {
        indexMethod(index) {
            return parseInt(index) + 1 + this.page_size * (this.current_page - 1);
        },
        handleSizeChange(val) {
            this.page_size = val;
            this.table_change();
        },
        handleCurrentChange(val) {
            this.current_page = val;
            this.table_change();
        },
        table_change() {
            var com = this;
            axios.get('api/orders', {params: {
                limit: com.page_size, 
                offset: com.page_size * (com.current_page - 1), 
                name: com.form_search.name,
                phone: com.form_search.phone,
                status: com.form_search.status
            }})
            .then(function (response) {
                com.orders = response.data[0];
                com.total = response.data[1];
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        clear() {
            this.current_page = 1;
            this.page_size = 10;
            this.form_search.name = '';
            this.form_search.phone = '';
            this.table_change();
        },
        search() {
            this.current_page = 1;
            this.page_size = 10;
            this.table_change();
        },
        edit_status(row) {
            var com = this;
            axios.put(`/api/orders/${row.id}`, {status: row.status})
            .then(function (response) {
                com.$notify({
                    title: 'Success',
                    message: 'Successful update order status.',
                    type: 'success'
                });
                com.table_change();
            })
            .catch(function (error) {
                console.log(error);
            });
        }
      }
    })
</script>
<script>
String.prototype.capitalize = function() {
    return this.replace(/(?:^|\s)\S/g, function(a) { return a.toUpperCase(); });
};
</script>
@endsection