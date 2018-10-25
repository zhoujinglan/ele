@extends("admin.layouts.main")
@section("title","分类添加")
@section("content")
    <a href="javascript:history.go(-1)" class="btn btn-info">返回</a>
    <form action="" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="form-group">
            <label>分类名称</label>
            <input type="text" class="form-control"  placeholder="名称" name="name" value="{{old("name")}}">
        </div>


        <div class="form-group">
            <label>图片</label>
            <input type="file" name="img" >
        </div>


        <button type="submit" class="btn btn-default">添加</button>
    </form>

@endsection

