@extends("admin.layouts.main")
@section("title","订单列表")
@section("content")
    <div class="rows">
        <table class="table table-bordered">
            <tr>
                {{--<td>时间</td>--}}
                <td>店铺id</td>
                <td>金额</td>
                <td>数量</td>

                <td>操作</td>
            </tr>
            @foreach($orders as $order)
                <tr>
                    {{--<td>{{$order->date}}</td>--}}
                    <td>{{$order->shop->shop_name}}</td>
                    <td>{{$order->money}}</td>
                    <td>{{$order->nums}}</td>
                    <td>

                    <a href="#">查看</a>
                    </td>
                </tr>
            @endforeach
        </table>
{{--    {{$orders->links()}}--}}

    </div>
    </div>
    @endsection