@extends("shop.layouts.main")
@section("title","菜品列表")
@section("content")
    <div class="rows">

        {{--搜索栏--}}
        <div>
            <form class="form-inline pull-right" method="get">
                <div class="form-group">

                    <select  name="category_id"  class="form-control">
                        <option value="">请选择商品分类</option>
                        @foreach($cates as $cate)
                            <option value="{{$cate->id}}">{{$cate->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">

                    <input type="text" class="form-control" value="{{old("minPrice")}}" size="5"  placeholder="最低价" name="minPrice">
                </div>
                -
                <div class="form-group">

                    <input type="text" class="form-control" size="5"  placeholder="最高价" value="{{old('maxPrice')}}" name="maxPrice">
                </div>
                <div class="form-group">

                    <input type="text" class="form-control" size="10"  placeholder="关键字" value="{{old("keyword")}}" name="keyword">
                </div>

                <button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-search"></i></button>
            </form>
        </div>
        <table class="table table-bordered">
            <tr>
                <td>id</td>
                <td>菜品名字</td>
                <td>店铺</td>
                <td>分类</td>
                <td>价格</td>
                <td>图片</td>
                <td>描述</td>
                <td>状态</td>
                <td>操作</td>
            </tr>
            @foreach($menus as $menu)
                <tr>
                    <td>{{$menu->id}}</td>
                    <td>{{$menu->goods_name}}</td>
                    <td>

                        @if($menu->menu_shop)
                            {{$menu->menu_shop->shop_name}}
                            @endif
                    </td>
                    <td>
                        @if($menu->menu_category)
                        {{$menu->menu_category->name}}
                            @endif
                    </td>
                    <td>{{$menu->goods_price}}</td>
                    <td>
                        <img src="/{{$menu->goods_img}}" height="60" alt="">
                    </td>

                    <td>{{$menu->description}}</td>
                    <td>
                        @if($menu->status == 1)
                           上架
                        @else
                           下架
                        @endif
                    </td>

                    <td>
                        <a href="{{route("menu.edit",$menu->id)}}" class="btn btn-success">编辑</a>
                        <a href="{{route("menu.del",$menu->id)}}" class="btn btn-danger">删除</a>

                    </td>
                </tr>
            @endforeach
        </table>
        {{$menus->appends($url)->links()}}
      </div>
    </div>
    @endsection