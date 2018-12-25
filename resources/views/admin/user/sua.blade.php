@extends('admin.layout.index')
@section('content')
<!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">User
                            <small>{{$user->name}}</small>
                        </h1>
                    </div>
                    <!-- /.col-lg-12 -->
                     @if(count($errors)>0)
                       
                            <div class="alert alert-danger">
                                 @foreach($errors->all() as $err)
                                    {{$err}}<br>
                                  @endforeach
                            </div>
                       
                    @endif
                    @if(session('thongbao'))
                        <div class="alert alert-success">{{session('thongbao')}}</div>
                    @endif
                    <div class="col-lg-7" style="padding-bottom:120px">
                        <form action="admin/user/sua/{{$user->id}}}" method="POST">
                           <input type="hidden" name="_token" value="{{csrf_token()}}" ">
                            <div class="form-group">
                                <label>Tên</label>
                                <input class="form-control" name="name" value="{{$user->name}}" placeholder="Nhập tên người dùng" />
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input class="form-control" readonly value="{{$user->email}}" name="email" type="email" placeholder="Nhập email" />
                            </div>
                            <div class="form-group">
                                <input type="checkbox" id="changePassword" name="changePassword">
                                <label>Đổi mật khẩu</label>
                                <input class="form-control password" type="password"  name="password" placeholder="Nhập password" disabled="" />
                            </div>
                            <div class="form-group">
                                <label>nhập lại password</label>
                                <input class="form-control password" type="password" disabled="" name="passwordAgain" placeholder="Nhập lại password" />
                            </div>
                            <div class="form-group">
                                <label>Quyền người dùng</label>
                                <label class="radio-inline">
                                    <input name="quyen" value="0" 
                                            @if($user->quyen==0)
                                                {{"checked"}}
                                            @endif
                                     type="radio">Thường
                                </label>
                                <label class="radio-inline">
                                    <input name="quyen"
                                        @if($user->quyen==1)
                                                {{"checked"}}
                                         @endif
                                     value="1" type="radio">Admin
                                </label>
                            </div>
                            <button type="submit" class="btn btn-default">Sửa</button>
                            <button type="reset" class="btn btn-default">Làm mới</button>
                        <form>
                    </div>
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->
@endsection
@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#changePassword').change(function(event) {
                if($(this).is(":checked")){
                    $(".password").removeAttr('disabled');
                }
                else{
                    $(".password").attr('disabled','');
                }
            });
        });
    </script>
@endsection