@extends('../layouts.master')

@section('css')
<!-- element_ui_css -->
<link rel="stylesheet" href="https://unpkg.com/element-ui/lib/theme-chalk/index.css">
<!-- end_element_ui_css -->
@endsection

@section('content')
        <div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Manage Users</h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="content mt-3" id="app">
            <div class="animated fadeIn">
                <div class="card">
                    <div class="card-header">
                        <el-button type="primary" @click="go_add_user()">Add User</el-button>
                    </div>
                    <div class="card-body">
                        <el-form :inline="true" :model="form_search" class="demo-form-inline">
                            <el-form-item label="Name">
                                <el-input v-model="form_search.name" placeholder="Name"></el-input>
                            </el-form-item>
                            <el-form-item label="Username">
                                <el-input v-model="form_search.username" placeholder="Username"></el-input>
                            </el-form-item>
                            <el-form-item label="Email">
                                <el-input v-model="form_search.email" placeholder="Email address"></el-input>
                            </el-form-item>
                            <el-form-item label="Role">
                                <el-select v-model="form_search.role" placeholder="Role">
                                    <el-option label="Superadmin" value="superadmin"></el-option>
                                    <el-option label="Customer" value="customer"></el-option>
                                </el-select>
                            </el-form-item>
                            <el-form-item>
                                <el-button @click="search" type="primary">Search</el-button>
                                <el-button @click="clear" type="default">Clear</el-button>
                            </el-form-item>
                        </el-form>
                        <hr>
                        <el-table :data="users" stripe border style="width: 100%">
                            <el-table-column type="index" :index="indexMethod" width="50" align="center"></el-table-column>
                            <el-table-column prop="name" label="Name"></el-table-column>
                            <el-table-column prop="username" label="Username"></el-table-column>
                            <el-table-column prop="email" label="Email address"></el-table-column>
                            <el-table-column prop="role" label="Role" width="120" align="center"></el-table-column>
                            <el-table-column prop="action" label="Action" width="180" align="center">
                                <template slot-scope="scope">
                                    <el-button
                                    size="mini"
                                    @click="viewUserProfile(scope.$index, scope.row)">View</el-button>
                                    <el-button
                                    size="mini"
                                    type="primary"
                                    @click="editUserProfile(scope.$index, scope.row)">Edit</el-button>
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
            users: [],
            form_search: {
                name: '',
                username: '',
                email: '',
                role: null,
            },
            current_page: 1,
            page_size: 10,
            total: null
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
            axios.get('api/users', {params: {
                limit: com.page_size, 
                offset: com.page_size * (com.current_page - 1), 
                name: com.form_search.name,
                username: com.form_search.username,
                email: com.form_search.email,
                role: com.form_search.role
            }})
            .then(function (response) {
                var users = response.data[0];
                com.users = users.map(function(user) {
                    user.role = user.role === 'superadmin' ? 'Superadmin' : 'Customer';
                    return user;
                });
                com.total = response.data[1];
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        viewUserProfile(index, row) {
            window.location.href = `/users/profiles/${row.username}`;
        },
        editUserProfile(index, row) {
            window.location.href = `/users/profiles/${row.username}/edit`;
        },
        go_add_user() {
            window.location.href = `/users/add`;
        },
        clear() {
            this.current_page = 1;
            this.page_size = 10;
            this.form_search.name = '';
            this.form_search.username = '';
            this.form_search.email = '';
            this.form_search.role = null;
            this.table_change();
        },
        search() {
            if (this.form_search.name.trim() === '' &&
                this.form_search.username.trim() === '' &&
                this.form_search.email.trim() === '' &&
                this.form_search.role === null
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
@endsection