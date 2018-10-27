
@extends("admin.layouts.main")
@section("title","店户列表")
@section("content")

<div class="rows" >
   <h3 style="color: #00a7d0"> >>>>>商户信息 </h3>
    <table class="table table-bordered">
        <tr>
            <td>账号</td>
            <td>申请名</td>
            <td>店铺名字</td>
            <td>logo</td>
            <td>邮箱</td>
            <td>操作</td>
        </tr>
        @foreach($users as $user)
            <tr>
                <td>{{$user->id}}</td>
                <td>{{$user->name}}</td>
                <td>@if($user->shop) {{$user->shop->shop_name}} @endif</td>
                <td><img src="/{{$user->img}}" height="50"></td>

                <td>{{$user->email}}</td>


                <td>
                    {{--<a href="#" class="btn btn-success">编辑</a>--}}
                    <a href="{{route("admin.user.edit",$user->id)}}" class="btn btn-warning">重置密码</a>
                    <a href="{{route("admin.user.del",$user->id)}}" class="btn btn-danger">删除</a>
                 @if(!$user->shop)
                        <a href="{{route("admin.shop.add",$user->id)}}" class="btn btn-info">添加店铺</a>
                     @endif
                </td>
            </tr>
        @endforeach
    </table>
</div>


@endsection
