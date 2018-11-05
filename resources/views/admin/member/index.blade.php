
@extends("admin.layouts.main")
@section("title","会员列表")
@section("content")
<div class="rows">

    <table class="table table-bordered">
        <tr>
            <td>会员id</td>
            <td>姓名</td>
            <td>电话号码</td>
            <td>余额</td>
            <td>积分</td>

            <td>操作</td>
        </tr>
        @foreach($members as $member)
            <tr>
                <td>{{$member->id}}</td>
                <td>{{$member->username}}</td>
                <td>{{$member->tel}}</td>
                <td>{{$member->money}}</td>
                <td>{{$member->jifen}}</td>

                <td>
                    <a href="#" class="btn btn-success">查看</a>
                    <a href="#" class="btn btn-danger">禁用</a>

                </td>
            </tr>
        @endforeach
    </table>
    {{$members->links()}}
</div>


@endsection
