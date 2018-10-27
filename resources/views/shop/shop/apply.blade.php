
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <title>店铺申请</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 shim 和 Respond.js 是为了让 IE8 支持 HTML5 元素和媒体查询（media queries）功能 -->
    <!-- 警告：通过 file:// 协议（就是直接将 html 页面拖拽到浏览器中）访问页面时 Respond.js 不起作用 -->
    <!--[if lt IE 9]>
    <script src="https://cdn.jsdelivr.net/npm/html5shiv@3.7.3/dist/html5shiv.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/respond.js@1.4.2/dest/respond.min.js"></script>
    <![endif]-->
</head>
<body>

    <div class="rows">
@include("shop.layouts._error")
 @include("shop.layouts._msg")
        <form class="form-horizontal" method="post" enctype="multipart/form-data" action="">
            {{csrf_field()}}
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
                <label for="discount" class="col-sm-2 control-label">店铺信息</label>
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
    </div>




<!-- jQuery (Bootstrap 的所有 JavaScript 插件都依赖 jQuery，所以必须放在前边) -->
<script src="https://cdn.jsdelivr.net/npm/jquery@1.12.4/dist/jquery.min.js"></script>
<!-- 加载 Bootstrap 的所有 JavaScript 插件。你也可以根据需要只加载单个插件。 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js"></script>
</body>
</html>



