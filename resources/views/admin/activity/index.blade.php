
@extends("admin.layouts.main")
@section("title","活动列表")
@section("content")
<div class="rows">
    {{--//搜索--}}
    <form class="form-inline pull-right" method="get">



        <div class="form-group">

            <select  name="time"  class="form-control">
                <option value="">活动时间</option>
                <option value="1">已开始</option>
                <option value="2">已结束</option>
                <option value="3">未开始</option>


            </select>
        </div>
        <div class="form-group">

            <input type="text" class="form-control" size="10"  placeholder="活动内容" name="keyword" value="{{request()->get("keyword")}}">
        </div>

        <button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-search"></i></button>
    </form>
    <br><br>
    <table class="table table-bordered">
        <tr>
            <td>活动编号</td>
            <td>活动名称</td>
            <td>活动详情</td>
            <td>活动开始时间</td>
            <td>活动结束时间</td>
            <td>操作</td>
        </tr>
        @foreach($activities as $activity)
            <tr>
                <td>{{$activity->id}}</td>
                <td>{{$activity->title}}</td>
                <td>{!!$activity->content!!}</td>
                <td>{{$activity->start_time}}</td>
                <td>{{$activity->end_time}}</td>
                <td>
                    <a href="{{route("activity.edit",[$activity->id])}}" class="btn btn-success">编辑</a>

                    <a href="{{route("activity.del",[$activity->id])}}" class="btn btn-danger">删除</a>

                </td>
            </tr>
        @endforeach
    </table>
    {{$activities->appends($url)->links()}}
</div>


@endsection
