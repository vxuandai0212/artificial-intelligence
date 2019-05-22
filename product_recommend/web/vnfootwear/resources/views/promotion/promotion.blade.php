@extends('../layouts.master')

@section('css')
<link rel="stylesheet" href="https://unpkg.com/element-ui/lib/theme-chalk/index.css">
<style>
.v-modal {
    z-index: -1!important;
}
</style>
@endsection

@section('content')
        <div class="breadcrumbs">
            <div class="page-header float-left">
                <div class="page-title">
                    <h1>Manage Promotion</h1>
                </div>
            </div>
        </div>

        <div class="content mt-3" id="app">
            <div class="animated fadeIn">
                <div class="card">
                    <div class="card-header">
                        <el-button type="primary" @click="openAdd">Add Promotion</el-button>
                    </div>
                    <div class="card-body">
                        <el-form :inline="true" :model="form_search" class="demo-form-inline">
                            <el-form-item label="Code">
                                <el-input v-model="form_search.code" placeholder="Promotion code"></el-input>
                            </el-form-item>
                            <el-form-item label="Discount">
                                <el-select v-model="form_search.discount" placeholder="Status">
                                    <el-option
                                        v-for="item in discounts"
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
                        <el-table :data="promotions" stripe border style="width: 100%">
                            <el-table-column type="index" :index="indexMethod" width="50" align="center"></el-table-column>
                            <el-table-column prop="code" label="Code"></el-table-column>
                            <el-table-column prop="discount" label="Discount" align="center">
                                <template slot-scope="scope">
                                    @{{scope.row.discount}}%
                                </template>
                            </el-table-column>
                            <el-table-column prop="action" label="Action" width="180" align="center">
                                <template slot-scope="scope">
                                    <el-button
                                    size="mini"
                                    type="primary"
                                    @click="editPromo(scope.$index, scope.row)"
                                    >Edit</el-button>
                                    <el-button
                                    size="mini"
                                    type="danger"
                                    @click="deletePromo(scope.$index, scope.row)"
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

                        <!-- Form Add-->
                        <el-dialog title="Add an promotion" :visible.sync="dialogAddVisible">
                            <el-form>
                                <el-form-item label="Code" :label-width="formLabelWidth">
                                    <el-input v-model="form_add.code" autocomplete="off"></el-input>
                                </el-form-item>
                                <el-form-item label="Discount" :label-width="formLabelWidth">
                                    <el-select v-model="form_add.discount" placeholder="Discount percentage">
                                        <el-option
                                            v-for="item in discounts"
                                            :key="item.value"
                                            :label="item.label"
                                            :value="item.value">
                                        </el-option>
                                    </el-select>
                                </el-form-item>
                            </el-form>
                            <span slot="footer" class="dialog-footer">
                                <el-button @click="dialogAddVisible = false">Close</el-button>
                                <el-button type="primary" @click="addPromo">Add</el-button>
                            </span>
                        </el-dialog>
                        <!-- Form Edit-->
                        <el-dialog title="Edit promotion" :visible.sync="dialogEditVisible">
                            <el-form>
                                <el-form-item label="Code" :label-width="formLabelWidth">
                                    <el-input v-model="form_edit.code" autocomplete="off"></el-input>
                                </el-form-item>
                                <el-form-item label="Discount" :label-width="formLabelWidth">
                                    <el-select v-model="form_edit.discount">
                                        <el-option
                                            v-for="item in discounts"
                                            :key="item.value"
                                            :label="item.label"
                                            :value="item.value">
                                        </el-option>
                                    </el-select>
                                </el-form-item>
                            </el-form>
                            <span slot="footer" class="dialog-footer">
                                <el-button @click="dialogEditVisible = false">Close</el-button>
                                <el-button type="primary" @click="updatePromo">Update</el-button>
                            </span>
                        </el-dialog>
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
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
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
            promotions: [],
            current_page: 1,
            page_size: 10,
            total: null,
            form_search: {
                code: '',
                discount: null
            },
            form_add: {
                code: '',
                discount: null,
            },
            form_edit: {
                id: null,
                code: '',
                discount: null
            },
            dialogEditVisible: false,
            dialogAddVisible: false,
            discounts: [
                {
                    value: '10',
                    label: '10%'
                }, {
                    value: '20',
                    label: '20%'
                }, {
                    value: '30',
                    label: '30%'
                }, {
                    value: '40',
                    label: '40%'
                }, {
                    value: '50',
                    label: '50%'
                }, {
                    value: '60',
                    label: '60%'
                }, {
                    value: '70',
                    label: '70%'
                }, {
                    value: '80',
                    label: '80%'
                }, {
                    value: '90',
                    label: '90%'
                }
            ],
            formLabelWidth: '120px',
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
            axios.get('api/promotions', {params: {
                limit: com.page_size, 
                offset: com.page_size * (com.current_page - 1), 
                code: com.form_search.code,
                discount: com.form_search.discount
            }})
            .then(function (response) {
                com.promotions = response.data[0];
                com.total = response.data[1];
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        openAdd() {
            var com = this;
            com.form_add.code = '';
            com.form_add.discount = null;
            com.dialogAddVisible = true;
        },
        addPromo() {
            var com = this;
            axios.post('api/promotions', {code: com.form_add.code, discount: com.form_add.discount})
            .then(function (response) {
                com.$notify({
                    title: 'Success',
                    message: 'Successful add a promotion code.',
                    type: 'success'
                });
                com.dialogAddVisible = false;
                com.form_add.code = '';
                com.form_add.discount = '';
                com.table_change();
            })
            .catch(function (error) {
                this.$notify.error({
                    title: 'Error',
                    message: error.message
                });
            });
        },
        editPromo(index, row) {
            var com = this;
            axios.get(`api/promotions/${row.id}`)
            .then(function (response) {
                com.form_edit.id = response.data.id;
                com.form_edit.code = response.data.code;
                com.form_edit.discount = response.data.discount;
                com.dialogEditVisible = true;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        updatePromo() {
            var com = this;
            axios.put(`/api/promotions/${com.form_edit.id}`, {
                code: com.form_edit.code, discount: com.form_edit.discount
            })
            .then(function (response) {
                com.$notify({
                    title: 'Success',
                    message: 'Successful update promotion',
                    type: 'success'
                });
                com.dialogEditVisible = false;
                com.table_change();
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        deletePromo(index, row) {
            var com = this;
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this promotion code!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    axios.delete(`/api/promotions/${row.id}`)
                    .then(function (response) {
                        swal("Poof! Your promotion code has been deleted!", {
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
                    swal("Your promotion is safe!");
                }
            });
        },
        clear() {
            this.current_page = 1;
            this.page_size = 10;
            this.form_search.code = '';
            this.form_search.discount = '';
            this.table_change();
        },
        search() {
            if (this.form_search.code.trim() === '' &&
                this.form_search.discount === ''
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