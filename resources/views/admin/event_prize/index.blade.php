
@extends("admin.layouts.main")
@section("title","抽奖活动")
@section("content")
<div class="rows">
    <h3 style="color: red"> >>>>>奖品 </h3>
    <table class="table table-bordered">
        <tr>
            <td>id</td>
            <td>名称</td>
            <td>活动id</td>
            <td>描述</td>
            <td>user_id</td>
            <td>操作</td>
        </tr>
        @foreach($prizes as $prize)
            <tr>
                <td>{{$prize->id}}</td>
                <td>{{$prize->name}}</td>

                <td>{{$prize->event_id}}</td>
                <td>{{$prize->description}}</td>
                <td>
                    @if(empty($prize->user_id))
                        无
                    @else
                    {{$prize->user->name}}
                        @endif
                </td>
                <td>
                    <a href="{{route("admin.event_prize.edit",[$prize->id])}}" class="btn btn-success">编辑</a>
                    <a href="{{route("admin.event_prize.del",[$prize->id])}}" class="btn btn-warning">删除</a>
                </td>
            </tr>
        @endforeach
    </table></div>


@endsection
