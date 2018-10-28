
@extends("admin.layouts.main")
@section("title","申请处理")
@section("content")
<div class="rows">


    <table class="table table-bordered">
        <tr>
            <td>申请编号</td>
            <td>申请账号</td>
            <td>图片</td>
            <td>申请分类</td>
            <td>店铺名字</td>
            <td>蜂鸟配送</td>
            <td>状态</td>
            <td>操作</td>
        </tr>
        @foreach($shops as $shop)
            <tr>
                <td>{{$shop->id}}</td>
                <td>
                    @if($shop->user)
                        {{$shop->user->name}}
                        @endif
                </td>
                <td><img src="/{{$shop->shop_img}}" width="50" alt=""></td>
                <td>
                    @if($shop->shop_category)
                    {{$shop->shop_category->name}}
                        @endif
                </td>
                <td>{{$shop->shop_name}}</td>
                <td>
                    @if($shop->fengniao)
                        是
                    @else
                        否
                    @endif

                <td>
                    @if($shop->status == 1)
                        已审核
                        @elseif($shop->status == 0)
                        <a href="{{route("admin.audit",$shop->id)}}">待审核</a>
                        @else
                        已禁用
                     @endif
                </td>

                <td>

                    <a href="#" class="btn btn-danger">删除</a>

                </td>
            </tr>
        @endforeach
    </table></div>


@endsection
