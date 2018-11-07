@extends("shop.layouts.main")
@section("title","订单列表")
@section("content")
    <div class="rows">

        <form action="" class="form-inline pull-right" method="get">


            <select name="status"class="form-control" >
                <option value="">选择状态</option>
                {{--状态(-1:已取消,0:待支付,1:待发货,2:待确认,3:完成)--}}
                <option value="-1">已取消</option>
                <option value="0">待支付</option>
                <option value="1">待发货</option>
                <option value="2">待确认</option>
                <option value="3">完成</option>
            </select>
            <input type="text" name="order_code" class="form-control" size="10" placeholder="订单号"
                   value="{{old("order_code")}}">
            <input type="submit" value="搜索" class="btn btn-default">
        </form>
        <table class="table table-bordered">
            <tr>
                <td>id</td>
                <td>member_id</td>
                <td>shop_id</td>
                <td>订单号</td>
                <td>收货人姓名</td>
                <td>电话</td>
                <td>地址</td>
                <td>金额</td>
                <td>状态</td>
                <td>操作</td>
            </tr>
            @foreach($orders as $order)
                <tr>
                    <td>{{$order->id}}</td>
                    <td>{{$order->user_id}}</td>
                    <td>{{$order->shop_id}}</td>
                    <td>{{$order->order_code}}</td>
                    <td>{{$order->name}}</td>
                    <td>{{$order->tel}}</td>
                    <td>{{$order->provence.$order->city.$order->area.$order->address}}</td>
                    <td>{{$order->total}}</td>
                    <td>

                        @if($order->status == -1)
                            已取消
                        @elseif($order->status == 0)
                          待付款
                        @elseif($order->status == 1)
                          待发货
                        @elseif($order->status == 2)
                         待确认收货
                            @else
                        完成
                            @endif
                    </td>


                    <td>
                        {{--{{route("menu_cate.del",$order->id)}}--}}
                        @if($order->status == -1)

                            <a href="#" class="btn btn-danger">删除</a>
                        @elseif($order->status == 0  )
                            <a href="{{route("order.status",[$order->id,-1])}}" class="btn btn-warning">取消订单</a>
                        @elseif($order->status == 1)
                            <a href="{{route("order.status",[$order->id,2])}}" class="btn btn-info">发货</a>
                        @elseif($order->status == 2)
                            <a href="{{route("order.status",[$order->id,3])}}" class="btn btn-success">待确认</a>
                        @elseif($order->status == 3)
                            <a href="#" class="btn btn-default">完成 </a>
                        @endif
                        <a href="{{route("shop.order.look",[$order->id])}}" class="btn btn-default" >查看</a>

                    </td>
                </tr>
            @endforeach
        </table>
    {{$orders->links()}}

    </div>
    </div>
    @endsection