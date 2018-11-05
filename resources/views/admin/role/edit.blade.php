@extends("admin.layouts.main")
@section("title","角色添加")
@section("content")
    <a href="javascript:history.go(-1)" class="btn btn-info">返回</a>
    <form action="" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="form-group">
            <label>角色名称</label>
            <input type="text" class="form-control"  placeholder="名称" name="name" value="{{$role->name}}">
        </div>


        <div class="form-group">
            <label>权限</label>
            @foreach($pers as $per)
                <input type="checkbox" name="pers[]" value="{{$per->id}}" {{in_array($per->id,$rol)?'checked':""}}>
                {{$per->intro}}
            @endforeach

        </div>


        <button type="submit" class="btn btn-default">修改</button>
    </form>

@endsection

