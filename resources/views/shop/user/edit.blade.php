


@extends("shop.layouts.main")
@section("title","修改密码")
@section("content")



    <div class="rows">
        <div class="box-header with-border">
            <h3 class="box-title">商家密码修改</h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <form class="form-horizontal" method="post" action="">
            {{ csrf_field() }}
            <div class="box-body">

                <table class="table ">
                    <tr>
                        <th style="text-align: center" > 商家呢称</th>
                        <th > {{old("name",$user->name)}}</th>
                    </tr>
                </table>

                <div class="form-group">
                    <label  class="col-sm-2 control-label">旧密码密码</label>
                    <div class="col-sm-10">
                        <input type="password" class="form-control" placeholder="Password" name="old_password" value="{{old("password")}}">
                    </div>
                </div>



                <div class="form-group">
                    <label  class="col-sm-2 control-label">新密码</label>

                    <div class="col-sm-10">
                        <input type="password" class="form-control" placeholder="Password" name="password" value="{{old("password")}}">
                    </div>
                </div>

                <div class="form-group">
                    <label  class="col-sm-2 control-label">确认密码</label>
                    <div class="col-sm-10">
                        <input type="password" class="form-control" placeholder="Password" name="password_confirmation" value="{{old("password")}}">
                    </div>
                </div>


            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <a href="javascript:history.go(-1)" class="btn btn-warning">返回</a>

                <button type="submit" class="btn btn-info pull-right">修改</button>
            </div>
            <!-- /.box-footer -->
        </form>
    </div>

@endsection
