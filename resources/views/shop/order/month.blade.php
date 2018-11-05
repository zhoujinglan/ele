@extends("shop.layouts.main")
@section("title","每月统计")
@section("content")
    <div class="rows">
        <table class="table table-bordered">
            <tr>
                <td>日期</td>
                <td>数量</td>
                <td>金额</td>

            </tr>
            @foreach($datas as $data)
                <tr>
                    <td>{{$data->date}}</td>
                    <td>{{$data->nums}}</td>
                    <td>{{$data->money}}</td>
                </tr>
            @endforeach
        </table></div>
    </div>
    @endsection