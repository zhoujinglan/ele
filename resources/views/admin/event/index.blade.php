
@extends("admin.layouts.main")
@section("title","抽奖活动")
@section("content")
<div class="rows">
    <h3 style="color: #00a7d0"> >>>>>抽奖活动 </h3>
    <table class="table table-bordered">
        <tr>
            <td>id</td>
            <td>名称</td>
            <td>内容</td>
            <td>开始时间</td>
            <td>结束 时间</td>
            <td>抽奖开始时间</td>
            <td>已报名/总人数</td>
            <td>是否开始</td>
            <td>操作</td>
        </tr>
        @foreach($events as $event)
            <tr>
                <td>{{$event->id}}</td>
                <td>{{$event->title}}</td>
                <td>{{$event->content}}</td>
                <td>{{date("Y-m-d", $event['start_time'])}}</td>
                <td>{{date("Y-m-d", $event['end_time'])}}</td>
                <td>{{date("Y-m-d H:i:s", $event['prize_time'])}}</td>
                <td>{{\App\Models\EventUser::where('event_id',$event->id)->count()}}/{{$event->num}}</td>
                <td>
                    @if($event->is_prize == 1)
                        开始
                    @elseif($event->is_prize == -1)
                        结束
                    @else
                        未开始
                    @endif
                </td>
                <td>
                    <a href="{{route("admin.event.edit",[$event->id])}}" class="btn btn-success">编辑</a>
                    <a href="{{route("admin.event.del",[$event->id])}}" class="btn btn-warning">删除</a>
                    @if($event->is_prize == 0)
                    <a href="{{route("admin.event.open",[$event->id])}}" class="btn btn-danger">开奖</a>
                        @endif
                </td>
            </tr>
        @endforeach
    </table></div>


@endsection
