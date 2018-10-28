@extends("admin.layouts.main")
@section("title","添加活动")
@section("content")
    <a href="javascript:history.go(-1)" class="btn btn-info">返回</a>


    <form class="form-horizontal" method="post" enctype="multipart/form-data" action="">
        {{csrf_field()}}

        <div class="form-group">
            <label  class="col-sm-2 control-label">活动名称</label>
            <div class="col-sm-10">
                <input type="text" class="form-control"  placeholder="活动名称" name="title" value="{{old("title")}}">
            </div>
        </div>


        <div class="form-group">
            <label for="start_send" class="col-sm-2 control-label">活动开始时间</label>
            <div class="col-sm-10">
                <input type="datetime-local"  class="form-control" placeholder="活动结束时间" name="start_time" value="{{old("start_time")}}">
            </div>
        </div>

        <div class="form-group">
            <label for="start_send" class="col-sm-2 control-label">活动结束时间</label>
            <div class="col-sm-10">
                <input type="datetime-local"  class="form-control" placeholder="活动结束时间" name="end_time" value="{{old("end_time")}}">
            </div>
        </div>

        <div class="form-group">
            <label for="start_send" class="col-sm-2 control-label">活动详情</label>
            <div class="col-sm-10">
            <script id="container" name="content" type="text/plain"></script>
            </div>
        </div>



        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-default">添加</button>
            </div>
        </div>
    </form>

@endsection

@section("js")
    <script type="text/javascript">
        var ue = UE.getEditor('container');
        ue.ready(function() {
            ue.execCommand('serverparam', '_token', '{{ csrf_token() }}'); // 设置 CSRF token.
        });
    </script>

    @endsection

