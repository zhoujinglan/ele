
@extends("shop.layouts.main")
@section("title","活动列表")
@section("content")
<div class="rows">
    <table class="table table-bordered">
        <tr>
            <td>活动编号</td>
            <td>活动名称</td>
            <td>活动详情</td>
            <td>活动开始时间</td>
            <td>活动开始时间</td>
        </tr>
        @foreach($activities as $activity)
            <tr>
                <td>{{$activity->id}}</td>
                <td>{{$activity->title}}</td>
                <td>{!!$activity->content!!}</td>
                <td>{{$activity->start_time}}</td>
                <td>{{$activity->end_time}}</td>

            </tr>
        @endforeach
    </table></div>


@endsection
