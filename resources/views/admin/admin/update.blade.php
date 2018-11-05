@extends("admin.layouts.main")
@section("title","管理员修改")
@section("content")
    <a href="javascript:history.go(-1)" class="btn btn-info">返回</a>
    <form action="" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="form-group">
            <label>名称</label>
            <input type="text" class="form-control"  placeholder="名称" name="name" value="{{$admin->name}}">
        </div>

        <div class="form-group">
            <label>邮箱</label>
            <input type="email" class="form-control"  placeholder="邮箱" name="email" value="{{$admin->email}}">
        </div>


        <div class="form-group">
            <label>权限</label>
            @foreach($roles as $role)
                <input type="checkbox" name="role[]" value="{{$role->id}}" {{in_array($role->name,$rol)?'checked':""}}>
                {{$role->name}}
            @endforeach

        </div>


        <button type="submit" class="btn btn-default">修改</button>
    </form>

@endsection

