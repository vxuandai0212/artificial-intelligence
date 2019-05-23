@extends('../layouts.master')

@section('css')
<link rel="stylesheet" href="https://unpkg.com/element-ui/lib/theme-chalk/index.css">
@endsection

@section('content')
        <div class="breadcrumbs">
            <div class="page-header float-left">
                <div class="page-title">
                    <h1>Manage Products</h1>
                </div>
            </div>
        </div>

        <div class="content mt-3" id="app">
            <div class="animated fadeIn">
                <div class="card">
                    <div class="card-header">
                        <el-button type="primary" @click="go_add_product">Add Product</el-button>
                    </div>
                    <div class="card-body">
                        <el-form :inline="true" :model="form_search" class="demo-form-inline">
                            <el-form-item label="Name">
                                <el-input v-model="form_search.name" placeholder="Product name"></el-input>
                            </el-form-item>
                            <el-form-item label="Code">
                                <el-input v-model="form_search.code" placeholder="Code"></el-input>
                            </el-form-item>
                            <el-form-item label="Category">
                                <el-select v-model="form_search.category" placeholder="Product category">
                                    <el-option label="Originals" value="originals"></el-option>
                                    <el-option label="Training" value="training"></el-option>
                                    <el-option label="Football" value="football"></el-option>
                                    <el-option label="Running" value="running"></el-option>
                                </el-select>
                            </el-form-item>
                            <el-form-item label="Genre">
                                <el-select v-model="form_search.genre" placeholder="Product genre">
                                    <el-option label="Men" value="men"></el-option>
                                    <el-option label="Women" value="women"></el-option>
                                    <el-option label="Kids" value="kids"></el-option>
                                </el-select>
                            </el-form-item>
                            <el-form-item>
                                <el-button @click="search" type="primary">Search</el-button>
                                <el-button @click="clear" type="default">Clear</el-button>
                            </el-form-item>
                        </el-form>
                        <hr>
                        <el-table :data="products" stripe border style="width: 100%">
                            <el-table-column type="index" :index="indexMethod" width="50" align="center"></el-table-column>
                            <el-table-column prop="image" label="Image" width="70">
                                <template slot-scope="scope">
                                    <img alt="scope.row.name" :src="scope.row.images">
                                </template>
                            </el-table-column>
                            <el-table-column prop="code" label="Code"></el-table-column>
                            <el-table-column prop="name" label="Name"></el-table-column>
                            <el-table-column prop="category" label="Category">
                                <template slot-scope="scope">
                                    @{{scope.row.category.capitalize()}}
                                </template>
                            </el-table-column>
                            <el-table-column prop="genre" label="Genre">
                                <template slot-scope="scope">
                                    @{{scope.row.genre.capitalize()}}
                                </template>
                            </el-table-column>
                            <el-table-column prop="price" label="Price">
                                <template slot-scope="scope">
                                    $@{{scope.row.price}}
                                </template>
                            </el-table-column>
                            <el-table-column prop="action" label="Action" width="180" align="center">
                                <template slot-scope="scope">
                                    <el-button
                                    size="mini"
                                    type="primary"
                                    @click="editProduct(scope.$index, scope.row)"
                                    >Edit</el-button>
                                    <el-button
                                    size="mini"
                                    type="danger"
                                    @click="deleteProduct(scope.$index, scope.row)"
                                    >Delete</el-button>
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

<!-- sweet alert -->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

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
            products: [],
            current_page: 1,
            page_size: 10,
            total: null,
            form_search: {
                name: '',
                code: '',
                category: '',
                genre: ''
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
            axios.get('api/products', {params: {
                limit: com.page_size, 
                offset: com.page_size * (com.current_page - 1), 
                name: com.form_search.name,
                code: com.form_search.code,
                category: com.form_search.category,
                genre: com.form_search.genre
            }})
            .then(function (response) {
                com.products = response.data[0];
                com.total = response.data[1];
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        editProduct(index, row) {
            window.location.href = row.edit_url;
        },
        deleteProduct(index, row) {
            var com = this;
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this product!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    axios.delete(`/api/products/${row.id}`)
                    .then(function (response) {
                        swal("Poof! Your product has been deleted!", {
                            icon: "success",
                        });
                        com.table_change();
                    })
                    .catch(function (error) {
                        this.$notify.error({
                            title: 'Error',
                            message: error.message
                        });
                    });
                } else {
                    swal("Your product is safe!");
                }
            });
        },
        go_add_product() {
            window.location.href = '/products/add';
        },
        clear() {
            this.current_page = 1;
            this.page_size = 10;
            this.form_search.name = '';
            this.form_search.code = '';
            this.form_search.category = '';
            this.form_search.genre = '';
            this.table_change();
        },
        search() {
            if (this.form_search.name.trim() === '' &&
                this.form_search.code.trim() === '' &&
                this.form_search.category === '' &&
                this.form_search.genre === ''
            ) {
                return this.$notify({
                    title: 'Warning',
                    message: 'Vui lòng điền điều kiện tìm kiếm.',
                    type: 'warning'
                });
            }
            
            this.current_page = 1;
            this.page_size = 10;
            this.table_change();
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