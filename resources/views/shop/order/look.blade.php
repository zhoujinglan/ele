@extends("shop.layouts.main")
@section("title","查看单条数据")
@section("content")
    <div class="rows">

        <table class="table table-bordered">
            <tr><td>订单号</td><td>{{$order->order_code}}</td></tr>
            <tr><td>收货人姓名</td><td>{{$order->name}}</td></tr>
            <tr><td>电话</td><td>{{$order->tel}}</td></tr>
            <tr><td>地址</td><td>{{$order->provence.$order->city.$order->area.$order->address}}</td></tr>
            <tr><td>金额</td><td>{{$order->total}}</td></tr>
            <tr><td>状态</td><td>{{$order->status}}</td></tr>
        </table>
{{--        {{$datas->appends($url)->links()}}--}}

    </div>
    @endsection