@extends("admin.layouts.main")
@section("title","权限修改")
@section("content")
    <a href="javascript:history.go(-1)" class="btn btn-info">返回</a>
    <form action="" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="form-group">
            <label>名称</label>
            <input type="text" class="form-control"  placeholder="名称" name="name" value="{{$per->name}}">
        </div>


        <div class="form-group">
            <label>描述</label>
            <input type="text" class="form-control"  placeholder="介绍" name="intro" value="{{$per->intro}}">
        </div>


        <button type="submit" class="btn btn-default">修改</button>
    </form>

@endsection

