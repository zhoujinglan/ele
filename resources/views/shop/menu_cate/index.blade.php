@extends("shop.layouts.main")
@section("title","菜品分类列表")
@section("content")
    <div class="rows">
        <table class="table table-bordered">
            <tr>
                <td>id</td>
                <td>分类名称</td>
                <td>店铺名字</td>
                <td>描述</td>
                <td>是否是默认分类</td>

                <td>操作</td>
            </tr>
            @foreach($categories as $category)
                <tr>
                    <td>{{$category->id}}</td>
                    <td>{{$category->name}}</td>
                    <td>

                        @if($category->shop)
                            {{$category->shop->shop_name}}
                            @endif
                    </td>

                    <td>{{$category->description}}</td>
                    <td>
                        @if($category->is_selected == 1)
                           是
                        @else
                            不是
                        @endif
                    </td>

                    <td>
                        <a href="{{route("menu_cate.edit",$category->id)}}" class="btn btn-success">编辑</a>
                        <a href="{{route("menu_cate.del",$category->id)}}" class="btn btn-danger">删除</a>

                    </td>
                </tr>
            @endforeach
        </table></div>
    </div>
    @endsection