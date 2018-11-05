
@extends("admin.layouts.main")
@section("title","权限列表")
@section("content")
<div class="rows">
    <a href="{{route("per.add")}}" class="btn btn-info">添加</a>
    <table class="table table-bordered">
        <tr>
            <td>id</td>
            <td>name</td>
            <td>描述</td>
            <td>操作</td>
        </tr>
        @foreach($pers as $per)
            <tr>
                <td>{{$per->id}}</td>
                <td>{{$per->name}}</td>

                <td>{{$per->intro}}</td>

                <td>
                    <a href="{{route("per.edit",[$per->id])}}" class="btn btn-success">编辑</a>
                    <a href="{{route("per.del",[$per->id])}}" class="btn btn-danger">删除</a>

                </td>
            </tr>
        @endforeach
    </table>
{{$pers->links()}}
</div>


@endsection
