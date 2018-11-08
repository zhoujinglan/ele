
@extends("shop.layouts.main")
@section("title","奖品查看")
@section("content")
<div class="rows">
    <h3 style="color: red"> >>>>>中奖名单 </h3>
    <table class="table table-bordered">
        <tr>
            <td>id</td>
            <td>名称</td>
            <td>活动id</td>
            <td>描述</td>
            <td>user_id</td>

        </tr>
        @foreach($prizes as $prize)
            <tr>
                <td>{{$prize->id}}</td>
                <td>{{$prize->name}}</td>

                <td>{{$prize->event->title}}</td>
                <td>{{$prize->description}}</td>
                <td>
                    @if(empty($prize->user_id))
                        无
                    @else
                    {{$prize->user->name}}
                        @endif
                </td>

            </tr>
        @endforeach
    </table></div>


@endsection
