
@extends("admin.layouts.main")
@section("title","申请处理")
@section("content")
<div class="rows">
    <a href="{{route("cate.add")}}" class="btn btn-info">添加</a>
    <table class="table table-bordered">
        <tr>
            <td>id</td>
            <td>分类名称</td>
            <td>图片</td>
            <td>状态</td>
            <td>排名</td>

            <td>操作</td>
        </tr>
        @foreach($categories as $category)
            <tr>
                <td>{{$category->id}}</td>
                <td>{{$category->name}}</td>
                <td><img src="/{{$category->img}}" width="50" alt=""></td>




                <td>
                    @if($category->status)
                       启用
                        @else
                       禁用
                     @endif
                </td>
                <td>{{$category->sort}}</td>

                <td>
                    <a href="{{route("cate.edit",$category->id)}}" class="btn btn-success">编辑</a>
                    <a href="{{route("cate.del",$category->id)}}" class="btn btn-danger">删除</a>

                </td>
            </tr>
        @endforeach
    </table></div>


@endsection
