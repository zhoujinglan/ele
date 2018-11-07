@extends("shop.layouts.main")
@section("title","菜品每日统计")
@section("content")
    <div class="rows">

        <form action="" class="form-inline pull-right" method="get">

            <input type="date" name="start" class="form-control" size="2" placeholder="开始日期"
                   value="{{old("start")}}"> -
            <input type="date" name="end" class="form-control" size="2" placeholder="结束日期"
                   value="">
            <select name="month"class="form-control" >
                <option value="">选择月份</option>
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

                <td>日期</td>
                <td>名字</td>
                <td>数量</td>
                <td>单价</td>

            </tr>
            @foreach($datas as $data)
                <tr>
                    <td>{{$data->created_at}}</td>
                    <td>{{$data->goods_name}}</td>
                    <td>{{$data->amount}}</td>
                    <td>{{$data->goods_price}}</td>
                </tr>
            @endforeach
        </table>
        {{$datas->appends($url)->links()}}
    </div>
    </div>
    @endsection