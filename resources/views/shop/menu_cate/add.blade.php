@extends("shop.layouts.main")
@section("title","菜品分类添加")
@section("content")
    <form class="form-horizontal" method="post" enctype="multipart/form-data" action="">
        {{csrf_field()}}
        <div class="form-group">
            <label class="col-sm-2 control-label">菜品名称</label>
            <div class="col-sm-10">
                <input type="text" class="form-control"  placeholder="菜品名称" name="name" value="{{old("name")}}">
            </div>
        </div>


        <div class="form-group">
            <label  class="col-sm-2 control-label">菜品编号</label>
            <div class="col-sm-10">
                <input type="text"  class="form-control"  placeholder="菜品编号" name="type_accumulation" value="{{old("type_accumulation")}}">
            </div>
        </div>




        <div class="form-group">
            <label  class="col-sm-2 control-label">描述</label>
            <div class="col-sm-10">
                <textarea rows="4"  class="form-control" name="description" placeholder="描述"></textarea>

            </div>
        </div>



        <div class="form-group">
            <label for="discount" class="col-sm-2 control-label">是否是默认分类</label>
            <div class="col-sm-10">
                <input name="is_selected" type="radio" value="1"  @if (old("is_selected")==1) checked @endif>是
                <input name="is_selected" type="radio" value="0" @if(old("is_selected")==0) checked @endif>否

            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-default">添加</button>
            </div>
        </div>
    </form>
    @endsection



