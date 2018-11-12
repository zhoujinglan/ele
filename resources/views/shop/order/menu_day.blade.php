@extends("shop.layouts.main")
@section("title","菜品每日统计")
@section("content")
    <div class="rows">

        <form action="" class="form-inline pull-right" method="get">

            <select name="day"class="form-control" >
                <option value="">选择时间</option>
                @foreach($months as $month)
                <option value="{{$month->date}}">{{$month->date}}</option>
                    @endforeach
            </select>
            <input type="text" name="name" class="form-control" size="5" placeholder="菜名"
                   value="{{old("name")}}">
            <input type="submit" value="搜索" class="btn btn-default">
        </form>

        <table class="table table-bordered">
            <br>
            <br>
            <tr>
                <td>商品id</td>
                <td>名字</td>
                <td>数量</td>
                <td>金额</td>

            </tr>
            @foreach($datas as $data)
                <tr>
                    <td>{{$data->goods_id}}</td>
                    <td>{{$data->goods_name}}</td>
                    <td>{{$data->nums}}</td>
                    <td>{{$data->price}}</td>
                </tr>
            @endforeach
        </table>
{{--        {{$datas->appends($url)->links()}}--}}
    </div>
    </div>
    @endsection