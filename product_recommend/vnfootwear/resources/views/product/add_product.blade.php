@extends('../layouts.master')

@section('css')
<!-- element_ui_css -->
<link rel="stylesheet" href="https://unpkg.com/element-ui/lib/theme-chalk/index.css">
<!-- end_element_ui_css -->

<!-- summernote_css -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote-lite.css" rel="stylesheet">
<style>
.note-editor.note-frame .note-editing-area .note-editable {
    width: 85%;
    padding: 20px;
    margin: auto;
    overflow-y: scroll;
}
.avatar-upload {
  position: relative;
  max-width: 50%;
  margin: auto;
}
.avatar-upload .avatar-edit {
  position: absolute;
  right: 12px;
  z-index: 1;
  top: 10px;
}
.avatar-upload .avatar-edit input {
  display: none;
}
.avatar-upload .avatar-edit input + label {
  display: inline-block;
  width: 34px;
  height: 34px;
  margin-bottom: 0;
  border-radius: 100%;
  background: #FFFFFF;
  border: 1px solid transparent;
  box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.12);
  cursor: pointer;
  font-weight: normal;
  transition: all 0.2s ease-in-out;
}
.avatar-upload .avatar-edit input + label:hover {
  background: #f1f1f1;
  border-color: #d6d6d6;
}
.avatar-upload .avatar-edit input + label:after {
  content: "";
  font-family: "FontAwesome";
  color: #757575;
  position: absolute;
  top: -3px;
  left: 0;
  right: 0;
  text-align: center;
  margin: auto;
}
.avatar-upload .avatar-preview {
  height: 192px;
  position: relative;
  border: 6px solid #F8F8F8;
  box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.1);
}
.avatar-upload .avatar-preview > div {
  width: 100%;
  height: 100%;
  background-size: cover;
  background-repeat: no-repeat;
  background-position: center;
}
</style>
<!-- end_summernote_css -->
@endsection

@section('content')
        <div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="{{ route('admin.product') }}">Manage Products</a></li>
                            <li class="active">Add Product</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="content mt-3" id="app">
            <div class="animated fadeIn">
                <div class="card">
                    <div class="card-body">
                        <el-form ref="form" :model="form" label-width="120px">
                            <el-form-item label="Product Name">
                                <el-input v-model="form.name"></el-input>
                            </el-form-item>
                            <el-form-item label="Product Code">
                                <el-input v-model="form.code"></el-input>
                            </el-form-item>
                            <el-form-item label="Category">
                                <el-select v-model="form.category" placeholder="Select category">
                                    <el-option
                                        v-for="item in categories"
                                        :key="item.value"
                                        :label="item.label"
                                        :value="item.value">
                                    </el-option>
                                </el-select>
                            </el-form-item>
                            <el-form-item label="Genre">
                                <el-select v-model="form.genre" placeholder="Select genre">
                                    <el-option
                                        v-for="item in genres"
                                        :key="item.value"
                                        :label="item.label"
                                        :value="item.value">
                                    </el-option>
                                </el-select>
                            </el-form-item>
                            <el-form-item label="Size">
                                <el-select v-model="form.size" multiple placeholder="Select size">
                                    <el-option
                                        v-for="item in sizes"
                                        :key="item.value"
                                        :label="item.label"
                                        :value="item.value">
                                    </el-option>
                                </el-select>
                            </el-form-item>
                            <el-form-item label="Number colors">
                                <el-select v-model="colors" placeholder="Select number of colors">
                                    <el-option
                                        v-for="item in color_num"
                                        :key="item.value"
                                        :label="item.label"
                                        :value="item.value">
                                    </el-option>
                                </el-select>
                            </el-form-item>
                            <el-form-item label="Choose colors">
                                <el-color-picker v-for="i in colors" :key="i" v-model="form.color[i-1]"></el-color-picker>
                            </el-form-item>
                            <el-form-item label="Price">
                                <el-input v-model="form.price"></el-input>
                            </el-form-item>
                            <el-form-item label="Thumbnail">
                                <div class="avatar-upload">
                                    <div class="avatar-edit">
                                        <input type='file' id="imageUpload" @change="onImageChange" accept=".png, .jpg, .jpeg" />
                                        <label for="imageUpload"></label>
                                    </div>
                                    <div class="avatar-preview">
                                        <div id="imagePreview" :style="{'background-image': 'url(' + image + ')'}">
                                        </div>
                                    </div>
                                </div>
                            </el-form-item>
                            <el-form-item>
                                <el-button @click="back">Cancel</el-button>
                                <template v-if="adding">
                                    <el-button type="primary" :disabled="true">Adding</el-button>
                                </template>
                                <template v-else>
                                    <el-button type="primary" @click="add">Add</el-button>
                                </template>
                            </el-form-item>
                        </el-form>
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

<script src="https://unpkg.com/vue/dist/vue.js"></script>
<script src="https://unpkg.com/element-ui/lib/index.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/element-ui/2.4.7/locale/vi.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.18.0/axios.min.js"></script>
<script>
    ELEMENT.locale(ELEMENT.lang.vi)
</script>
<script>
    var app = new Vue({
      el: '#app',
      data: function() {
        return {
            form: {
                name: '',
                code: '',
                category: '',
                genre: '',
                size: [],
                color: [],
                price: null
            },
            categories: [{
                    value: 'originals',
                    label: 'Originals'
                }, {
                    value: 'training',
                    label: 'Training'
                }, {
                    value: 'football',
                    label: 'Football'
                }, {
                    value: 'running',
                    label: 'Running'
            }],
            genres: [{
                    value: 'men',
                    label: 'Men'
                }, {
                    value: 'women',
                    label: 'Women'
                }, {
                    value: 'kids',
                    label: 'Kids'
            }],
            sizes: [{
                    value: 20,
                    label: 20
                }, {
                    value: 21,
                    label: 21
                }, {
                    value: 22,
                    label: 22
                }, {
                    value: 23,
                    label: 23
                }, {
                    value: 24,
                    label: 24
                }, {
                    value: 25,
                    label: 25
                }, {
                    value: 26,
                    label: 26
                }, {
                    value: 27,
                    label: 27
                }, {
                    value: 28,
                    label: 28
                }, {
                    value: 29,
                    label: 29
                }, {
                    value: 30,
                    label: 30
            }],
            color_num: [
                {
                    value: 1,
                    label: 1
                }, {
                    value: 2,
                    label: 2
                }, {
                    value: 3,
                    label: 3
                }, {
                    value: 4,
                    label: 4
                }, {
                    value: 5,
                    label: 5
                }
            ],
            colors: 1,
            image: '/images/album/default.png',
            adding: false,
        }
      },
      methods: {
        add() {
            if (this.form.name.trim() === '') {
                return this.$notify({
                    title: 'Warning',
                    message: 'Name is required',
                    type: 'warning'
                });
            }
            if (this.form.code.trim() === '') {
                return this.$notify({
                    title: 'Warning',
                    message: 'Code is required',
                    type: 'warning'
                });
            }
            if (this.form.category === '') {
                return this.$notify({
                    title: 'Warning',
                    message: 'Product need at least one category',
                    type: 'warning'
                });
            }
            if (this.form.genre === '') {
                return this.$notify({
                    title: 'Warning',
                    message: 'Product need at least one genre',
                    type: 'warning'
                });
            }
            if (this.form.size.length === 0) {
                return this.$notify({
                    title: 'Warning',
                    message: 'Product need at least one size',
                    type: 'warning'
                });
            }
            if (this.form.color.length === 0) {
                return this.$notify({
                    title: 'Warning',
                    message: 'Product need at least one color',
                    type: 'warning'
                });
            }
            if (this.image === '/images/album/default.png') {
                return this.$notify({
                    title: 'Warning',
                    message: 'Thumbnail is required',
                    type: 'warning'
                });
            }
            if (this.form.price === "") {
                return this.$notify({
                    title: 'Warning',
                    message: 'Price is required',
                    type: 'warning'
                });
            }
            this.form.size = this.form.size.join();
            this.form.color = this.form.color.join();
            this.form.image = this.image;
            this.adding = true;
            var com = this;
            axios.post('/api/products', com.form)
            .then(function (response) {
                com.$notify({
                    title: 'Success',
                    message: 'Successful add a product',
                    type: 'success'
                });
                com.adding = false;
                setTimeout(function() {
                    window.location.href = `/products`;
                }, 1500)
            })
            .catch(function (error) {
                console.log(error);
                com.$notify.error({
                    title: 'Error',
                    message: error
                });
                com.adding = false;
            });
        },
        back() {
            window.location.href = `/products`;
        },
        onImageChange(e) {
            let files = e.target.files || e.dataTransfer.files;
            if (!files.length)
                return;
            this.createImage(files[0]);
        },
        createImage(file) {
            let reader = new FileReader();
            let vm = this;
            reader.onload = (e) => {
                vm.image = e.target.result;
            };
            reader.readAsDataURL(file);
        }
      }
    })
</script>
@endsection