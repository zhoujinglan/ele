@extends("admin.layouts.main")
@section("title","权限添加")
@section("content")
    <a href="javascript:history.go(-1)" class="btn btn-info">返回</a>
    <form action="" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="form-group">
            <label>名称</label>
            <select name="name" class="form-control" >
                <option value="">请选择权限名</option>
                @foreach($urls as $url)
                <option value="{{$url}}">{{$url}}</option>
                    @endforeach
            </select>
        </div>


        <div class="form-group">
            <label>介绍</label>
            <input type="text" class="form-control"  placeholder="介绍" name="intro" value="{{old("name")}}">
        </div>


        <button type="submit" class="btn btn-default">添加</button>
    </form>

@endsection

