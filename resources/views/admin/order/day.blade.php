@extends("admin.layouts.main")
@section("title","每日统计")
@section("content")
    <div class="rows">
        <form action="" class="form-inline pull-right" method="get">

            <input type="date" name="start" class="form-control" size="2" placeholder="开始日期"
                   value="{{old("start")}}"> -
            <input type="date" name="end" class="form-control" size="2" placeholder="结束日期"
                   value="">
            <input type="submit" value="搜索" class="btn btn-default">
        </form>
        <table class="table table-bordered">
            <br>
            <br>
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
        </table>
{{--        {{$datas->appends($url)->links()}}--}}
    </div>
    </div>
    @endsection