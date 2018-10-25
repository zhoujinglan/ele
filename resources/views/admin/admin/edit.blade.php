
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <title>资料修改</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 shim 和 Respond.js 是为了让 IE8 支持 HTML5 元素和媒体查询（media queries）功能 -->
    <!-- 警告：通过 file:// 协议（就是直接将 html 页面拖拽到浏览器中）访问页面时 Respond.js 不起作用 -->
    <!--[if lt IE 9]>
    <script src="https://cdn.jsdelivr.net/npm/html5shiv@3.7.3/dist/html5shiv.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/respond.js@1.4.2/dest/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div class="container">
 @include("shop.layouts._error")
   @include("shop.layouts._msg")

    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">管理员资料修改</h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <form class="form-horizontal" method="post" action="">
            {{ csrf_field() }}
            <div class="box-body">

                    <table class="table ">
                        <tr>
                            <th style="text-align: center" > 管理员姓名</th>
                            <th > {{old("name",$admin->name)}}</th>
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

</div>


<!-- jQuery (Bootstrap 的所有 JavaScript 插件都依赖 jQuery，所以必须放在前边) -->
<script src="https://cdn.jsdelivr.net/npm/jquery@1.12.4/dist/jquery.min.js"></script>
<!-- 加载 Bootstrap 的所有 JavaScript 插件。你也可以根据需要只加载单个插件。 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js"></script>
</body>
</html>

