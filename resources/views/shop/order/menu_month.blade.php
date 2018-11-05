@extends("shop.layouts.main")
@section("title","菜品每月统计")
@section("content")
    <div class="rows">

        <table class="table table-bordered">
            <br>
            <br>
            <tr>

                <td>日期</td>
                <td>名字</td>
                <td>数量</td>
                <td>金额</td>

            </tr>
            @foreach($datas as $data)
                <tr>
                    <td>{{$data->date}}</td>
                    <td>{{$data->goods_name}}</td>
                    <td>{{$data->nums}}</td>
                    <td>{{$data->money}}</td>
                </tr>
            @endforeach
        </table>
{{--        {{$datas->appends($url)->links()}}--}}
    </div>
    </div>
    @endsection