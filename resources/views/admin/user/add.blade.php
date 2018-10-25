@extends("admin.layouts.main")
@section("title","添加商户")
@section("content")
    <a href="javascript:history.go(-1)" class="btn btn-info">返回</a>


    <form class="form-horizontal" method="post" enctype="multipart/form-data" action="">
        {{csrf_field()}}

        <div class="box-body">
            <div class="form-group">
                <label  class="col-sm-2 control-label">商家姓名</label>

                <div class="col-sm-10">
                    <input type="text" class="form-control" placeholder="商家姓名" name="name" value="{{old("name")}}">

                </div>
            </div>
            <div class="form-group">
                <label  class="col-sm-2 control-label">登录密码</label>

                <div class="col-sm-10">
                    <input type="password" class="form-control" placeholder="Password" name="password" value="{{old("password")}}">
                </div>
            </div>
            <div class="form-group">
                <label  class="col-sm-2 control-label">邮箱</label>

                <div class="col-sm-10">
                    <input type="email" class="form-control" placeholder="email" name="email" value="{{old("email")}}">
                </div>
            </div>

        </div>
        <div class="form-group">
            <label for="shop_name" class="col-sm-2 control-label">店铺名称</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="shop_name" placeholder="店铺名称" name="shop_name" value="{{old("shop_name")}}">
            </div>
        </div>


        <div class="form-group">
            <label  class="col-sm-2 control-label">店铺分类</label>
            <div class="col-sm-10">
                <select name="shop_category_id" class="form-control">
                    <option value="">请选择分类</option>
                    @foreach($categories as $category)
                        <option value="{{$category->id}}">{{$category->name}}</option>
                    @endforeach
                </select>

            </div>
        </div>

        <div class="form-group">
            <label  class="col-sm-2 control-label">店铺图片</label>
            <div class="col-sm-10">
                <input type="file" class="" name="img">

            </div>
        </div>

        <div class="form-group">
            <label for="start_send" class="col-sm-2 control-label">起送金额</label>
            <div class="col-sm-10">
                <input type="text"  class="form-control" id="shop_rating" placeholder="起送金额" name="start_send" value="{{old("start_send")}}">
            </div>
        </div>

        <div class="form-group">
            <label for="send_cost" class="col-sm-2 control-label">配送金额</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="send_cost" placeholder="配送金额" name="send_cost" value="{{old("send_cost")}}">
            </div>
        </div>

        <div class="form-group">
            <label for="shop_rating" class="col-sm-2 control-label">店铺评分</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="shop_rating" placeholder="评分" name="shop_rating" value="{{old("shop_rating")}}">
            </div>
        </div>

        <div class="form-group">
            <label for="notice" class="col-sm-2 control-label">店铺公告</label>
            <div class="col-sm-10">
                <textarea rows="4"  class="form-control" name="notice" placeholder="店铺公告"></textarea>

            </div>
        </div>

        <div class="form-group">
            <label for="discount" class="col-sm-2 control-label">店铺信息</label>
            <div class="col-sm-10">
                <textarea rows="4"    class="form-control" name="discount" placeholder="店铺信息"></textarea>

            </div>
        </div>

        <div class="form-group">
            <label for="discount" class="col-sm-2 control-label"></label>
            <div class="col-sm-10">
                <input name="brand" type="checkbox" value="1"  @if (old("brand")==1) checked @endif>品牌连锁店
                <input name="on_time" type="checkbox" value="1" @if(old("on_time")==1) checked @endif>准时送达
                <input name="fengniao" type="checkbox" value="1" @if(old("fengniao")==1) checked @endif>蜂鸟配送
                <input name="bao" type="checkbox" value="1" @if(old("bao")==1) checked @endif>保
                <input name="piao" type="checkbox" value="1" @if(old("piao")==1) checked @endif>票
                <input name="zhun" type="checkbox" value="1" @if(old("zhun")==1) checked @endif>准
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-default">提交申请</button>
            </div>
        </div>
    </form>

@endsection

