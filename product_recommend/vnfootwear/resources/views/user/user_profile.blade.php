@extends('../layouts.master')

@section('css')
<!-- element_ui_css -->
<link rel="stylesheet" href="https://unpkg.com/element-ui/lib/theme-chalk/index.css">
<!-- end_element_ui_css -->
<style>
.avatar-upload {
  position: relative;
  max-width: 205px;
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
  top: 0px;
  left: 0;
  right: 0;
  text-align: center;
  margin: auto;
}
.avatar-upload .avatar-preview {
  width: 192px;
  height: 192px;
  position: relative;
  border-radius: 100%;
  border: 6px solid #F8F8F8;
  box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.1);
}
.avatar-upload .avatar-preview > div {
  width: 100%;
  height: 100%;
  border-radius: 100%;
  background-size: cover;
  background-repeat: no-repeat;
  background-position: center;
}
</style>
@endsection

@section('content')
    <div id="app">
        <div class="breadcrumbs">
            <div class="page-header float-left">
                <div class="page-title">
                    <ol class="breadcrumb text-right">
                        <li><a href="{{ route('admin.user') }}">Users</a></li>
                        <li><a>Profiles</a></li>
                        <li class="active">@{{user_slug}}</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="content mt-3">
            <div class="animated fadeIn">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex flex-row-reverse">
                            <div class="p-2"></div>
                            <div class="p-2"><el-button type="primary" @click="go_edit()">Edit</el-button></div>
                            <div class="p-2"><el-button type="default" @click="go_users()">Back</el-button></div>
                        </div>
                    </div>
                    <div class="card-body">
                        <el-form ref="form" :model="user" label-width="120px">
                            <el-form-item label="Name">
                                <el-input v-model="user.name" :disabled="true"></el-input>
                            </el-form-item>
                            <el-form-item label="Userame">
                                <el-input v-model="user.username" :disabled="true"></el-input>
                            </el-form-item>
                            <el-form-item label="Email">
                                <el-input v-model="user.email" :disabled="true"></el-input>
                            </el-form-item>
                            <el-form-item label="Role">
                                <el-select v-model="user.role" :disabled="true">
                                    <el-option label="Superadmin" value="superadmin"></el-option>
                                    <el-option label="Customer" value="customer"></el-option>
                                </el-select>
                            </el-form-item>
                        </el-form>
                    </div>
                </div>
            </div><!-- .animated -->
        </div>
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
        var com = this;
        axios.get(`/api/users/${com.user_id}`)
        .then(function (response) {
            var user = response.data;
            user.status == 1 ? user.status = true : user.status = false;
            if (user.role_id == 1) {
                user.role_id = 'Superadmin';
            } else if (user.role_id == 2) {
                user.role_id = 'Admin';
            } else {
                user.role_id = 'Subcriber';
            }
            com.user = user;
        })
        .catch(function (error) {
            console.log(error);
        });
      },
      data: function() {
        return {
            user: {},
            user_id: {{$user_id}},
            username: `{{$username}}`
        }
      },
      methods: {
        onSubmit() {
            console.log('submit!');
        },
        go_users() {
            window.location.href = `/users`;
        },
        go_edit() {
            window.location.href = `/users/profiles/${this.username}/edit`;
        }
      }
    })
</script>
@endsection