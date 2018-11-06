@extends("admin.layouts.main")
@section("title","导航菜单添加")
@section("content")
    {{--<a href="javascript:history.go(-1)" class="btn btn-info">返回</a>--}}
    <form action="" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="form-group">
            <div class="form-group">
                <label>菜单名</label>
                <input type="text" class="form-control"  placeholder="菜单名" name="name" value="{{old("name")}}">
            </div>
            <label>名称</label>
            <select name="url" class="form-control" >
                <option value="">请选择权限名</option>
                @foreach($urls as $url)
                <option value="{{$url}}">{{$url}}</option>
                    @endforeach
            </select>
        </div>


        <div class="form-group">
            <label>父id</label>

            <select name="pid" class="form-control">
                <option value="0">父级</option>
                @foreach($navs as $nav)
                <option value="{{$nav->id}}">{{$nav->name}}</option>
                    @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>排序</label>
            <input type="text" class="form-control"  placeholder="排序" name="sort" value="">
        </div>


        <button type="submit" class="btn btn-default">添加</button>
    </form>

@endsection

