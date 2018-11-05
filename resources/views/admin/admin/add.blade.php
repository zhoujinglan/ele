@extends("admin.layouts.main")
@section("title","管理员添加")
@section("content")
    <a href="javascript:history.go(-1)" class="btn btn-info">返回</a>
    <form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="box-body">
            <div class="form-group">
                <label  class="col-sm-2 control-label">管理员姓名</label>

                <div class="col-sm-10">
                    <input type="text" class="form-control" placeholder="商家姓名" name="name" value="{{old("name")}}">

                </div>
            </div>
            <div class="form-group">
                <label  class="col-sm-2 control-label">登录密码</label>

                <div class="col-sm-10">
                    <input type="password" class="form-control" placeholder="Password" name="password" value="{{old("password")}}">
                </div>
            </div>
            <div class="form-group">
                <label  class="col-sm-2 control-label">邮箱</label>

                <div class="col-sm-10">
                    <input type="email" class="form-control" placeholder="email" name="email" value="{{old("email")}}">
                </div>
            </div>

            <div class="form-group">
                <label  class="col-sm-2 control-label">角色</label>

                <div class="col-sm-10">
                    @foreach($roles as $role)
                    <input  type="checkbox" name="role[]" value="{{$role->id}}">{{$role->name}}
                        @endforeach
                </div>
            </div>

        </div>
        <!-- /.box-body -->
        <div class="box-footer">

            <button type="submit" class="btn btn-info pull-right">添加</button>

        </div>

        <!-- /.box-footer -->
    </form>

@endsection

