
@extends("admin.layouts.main")
@section("title","角色列表")
@section("content")
<div class="rows">
    <a href="{{route("role.add")}}" class="btn btn-info">添加</a>
    <table class="table table-bordered">
        <tr>
            <td>id</td>
            <td>name</td>
            <td>权限</td>
            <td>操作</td>
        </tr>
        @foreach($roles as $role)
            <tr>
                <td>{{$role->id}}</td>
                <td>{{$role->name}}</td>

                <td>{{ json_encode($role->permissions()->pluck('intro'),JSON_UNESCAPED_UNICODE)}}
                </td>

                <td>
                    <a href="{{route("role.edit",[$role->id])}}" class="btn btn-success">编辑</a>
                    <a href="{{route("role.del",[$role->id])}}" class="btn btn-danger">删除</a>

                </td>
            </tr>
        @endforeach
    </table>

</div>


@endsection
