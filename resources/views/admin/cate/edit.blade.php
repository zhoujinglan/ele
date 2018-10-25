@extends("admin.layouts.main")
@section("title","修改分类")
@section("content")
    <a href="javascript:history.go(-1)" class="btn btn-info">返回</a>
    <form action="" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="form-group">
            <label>分类名称</label>
            <input type="text" class="form-control"  placeholder="名称" name="name" value="{{old("name",$cate->name)}}">
        </div>
        <div class="form-group">
            <label>分类排名</label>
            <input type="text" class="form-control"  placeholder="名称" name="sort" value="{{old("name",$cate->sort)}}">
        </div>

        <div class="form-group">
            <label>图片</label>
            <input type="file" name="img" >
            <img src="/{{$cate->img}}" width="50" alt="">
        </div>


        <button type="submit" class="btn btn-default">修改</button>
    </form>

@endsection

