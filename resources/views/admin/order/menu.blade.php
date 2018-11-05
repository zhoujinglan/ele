@extends("admin.layouts.main")
@section("title","菜品销量总统计")
@section("content")

        <table class="table table-bordered">

            <tr><td>销售量</td><td>{{$data[0]->nums}}</td></tr>
            <tr><td>销售总金额</td><td>{{$data[0]->total}}</td></tr>

        </table>
{{--        {{$datas->appends($url)->links()}}--}}
    </div>
    </div>
    @endsection