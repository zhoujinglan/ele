@extends("admin.layouts.main")
@section("title","抽奖活动添加")
@section("content")
    <a href="javascript:history.go(-1)" class="btn btn-info">返回</a>
    <form action="" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}

            <div class="form-group">
                <label>活动标题</label>
                <input type="text" class="form-control"  placeholder="活动标题" name="title" value="{{old("title")}}">
            </div>



        <div class="form-group">
            <label>活动开始时间</label>
            <input type="date" class="form-control" name="start_time" >
        </div>
        <div class="form-group">
            <label>活动开始结束</label>
            <input type="date" class="form-control" name="end_time" >
        </div>
        <div class="form-group">
            <label>抽奖开始时间</label>
            <input type="datetime-local" class="form-control" name="prize_time" >
        </div>
        <div class="form-group">
            <label>人数</label>
            <input type="text" class="form-control"  placeholder="人数" name="num" value="">
        </div>
        <div class="form-group">
        <label>内容</label>
        <textarea name="content"  class="form-control" placeholder="活动内容"    rows="5"></textarea>
        </div>


        <button type="submit" class="btn btn-default">添加</button>
    </form>

@endsection

