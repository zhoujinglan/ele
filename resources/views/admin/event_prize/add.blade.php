@extends("admin.layouts.main")
@section("title","奖品添加")
@section("content")
    <a href="javascript:history.go(-1)" class="btn btn-info">返回</a>
    <form action="" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}

            <div class="form-group">
                <label>奖品名称</label>
                <input type="text" class="form-control"  placeholder="奖品名称" name="name" value="{{old("name")}}">
            </div>


        <div class="form-group">
            <label>抽奖活动id</label>
            <select name="event_id" class="form-control" >
                <option value="">选择活动</option>
                @foreach($events as $event)
                <option value="{{$event->id}}">{{$event->title}}</option>
                    @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>user_id</label>
            <input type="text" class="form-control"  placeholder="中奖id" name="user_id" value="">
        </div>
        <div class="form-group">
        <label>详情</label>
        <textarea name="description"  class="form-control" placeholder="详情"    rows="5"></textarea>
        </div>


        <button type="submit" class="btn btn-default">添加</button>
    </form>

@endsection

