@extends("shop.layouts.main")
@section("title","菜品修改")
@section("content")
    <a href="javascript:history.go(-1)" class="btn btn-info">返回</a>
    <form class="form-horizontal" method="post" enctype="multipart/form-data" action="">
        {{csrf_field()}}
        <div class="form-group">
            <label class="col-sm-2 control-label">名称</label>
            <div class="col-sm-10">
                <input type="text" class="form-control"  placeholder="菜品名称" name="goods_name" value="{{$menu->goods_name}}">
            </div>
        </div>
        
        <div class="form-group">
            <label  class="col-sm-2 control-label">类型</label>
            <div class="col-sm-10">
                <select name="category_id"  class="form-control">
                    <option value="">选择菜品分类</option>
                    @foreach($cates as $cate)
                    <option value="{{$cate->id}}" @if($cate->id == $menu->category_id) selected @endif >{{$cate->name}}</option>
                        @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">价格</label>
            <div class="col-sm-10">
                <input type="text" class="form-control"  placeholder="价格" name="goods_price" value="{{$menu->goods_price}}">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">提示信息</label>
            <div class="col-sm-10">
                <input type="text" class="form-control"  placeholder="提示信息" name="tips" value="{{$menu->tips}}">
            </div>
        </div>

        {{--<div class="form-group">--}}
            {{--<label  class="col-sm-2 control-label">图片</label>--}}
            {{--<div class="col-sm-10">--}}
                {{--<input type="file" name="img">--}}
                {{--<img src="/{{$menu->goods_img}}" height="60" alt="">--}}
            {{--</div>--}}
        {{--</div>--}}

        <div class="form-group">
            <label class="col-sm-2 control-label">图片</label>

            <input type="hidden" name="goods_img" value="" id="goods_img">

            <!--dom结构部分-->
            <div id="uploader-demo" class="col-sm-10">
                <img src="{{$menu->goods_img}}" height="60" alt="">
                <br> <br>
                <!--用来存放item-->
                <div id="fileList" class="uploader-list"></div>
                <div id="filePicker">选择图片</div>
            </div>

        </div>


        <div class="form-group">
            <label  class="col-sm-2 control-label">描述</label>
            <div class="col-sm-10">
                <textarea rows="4"  class="form-control" name="description" placeholder="描述">{{$menu->description}}</textarea>

            </div>
        </div>



        <div class="form-group">
            <label for="discount" class="col-sm-2 control-label">是否上架</label>
            <div class="col-sm-10">
                <input name="status" type="radio" value="1"  @if ($menu->status == 1||old("status")==1) checked @endif>是
                <input name="status" type="radio" value="0" @if($menu->status ==0 ||old("status")==0) checked @endif>否

            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-default">修改</button>
            </div>
        </div>
    </form>
    @endsection

@section("js")
    <script>
        // 图片上传demo
        jQuery(function () {
            var $ = jQuery,
                $list = $('#fileList'),
                // 优化retina, 在retina下这个值是2
                ratio = window.devicePixelRatio || 1,

                // 缩略图大小
                thumbnailWidth = 100 * ratio,
                thumbnailHeight = 100 * ratio,

                // Web Uploader实例
                uploader;

            // 初始化Web Uploader
            uploader = WebUploader.create({

                // 自动上传。
                auto: true,

                formData: {
                    // 这里的token是外部生成的长期有效的，如果把token写死，是可以上传的。
                    _token:'{{csrf_token()}}'
                },


                // swf文件路径
                swf: '/webuploader/Uploader.swf',

                // 文件接收服务端。
                server: '{{route("menu.upload")}}',

                // 选择文件的按钮。可选。
                // 内部根据当前运行是创建，可能是input元素，也可能是flash.
                pick: '#filePicker',

                // 只允许选择文件，可选。
                accept: {
                    title: 'Images',
                    extensions: 'gif,jpg,jpeg,bmp,png',
                    mimeTypes: 'image/*'
                }
            });

            // 当有文件添加进来的时候
            uploader.on('fileQueued', function (file) {
                var $li = $(
                        '<div id="' + file.id + '" class="file-item thumbnail">' +
                        '<img>' +
                        '<div class="info">' + file.name + '</div>' +
                        '</div>'
                    ),
                    $img = $li.find('img');

                $list.html($li);

                // 创建缩略图
                uploader.makeThumb(file, function (error, src) {
                    if (error) {
                        $img.replaceWith('<span>不能预览</span>');
                        return;
                    }

                    $img.attr('src', src);
                }, thumbnailWidth, thumbnailHeight);
            });

            // 文件上传过程中创建进度条实时显示。
            uploader.on('uploadProgress', function (file, percentage) {
                var $li = $('#' + file.id),
                    $percent = $li.find('.progress span');

                // 避免重复创建
                if (!$percent.length) {
                    $percent = $('<p class="progress"><span></span></p>')
                        .appendTo($li)
                        .find('span');
                }

                $percent.css('width', percentage * 100 + '%');
            });

            // 文件上传成功，给item添加成功class, 用样式标记上传成功。
            uploader.on('uploadSuccess', function (file,data) {
                $('#' + file.id).addClass('upload-state-done');

                $("#goods_img").val(data.url);
            });

            // 文件上传失败，现实上传出错。
            uploader.on('uploadError', function (file) {
                var $li = $('#' + file.id),
                    $error = $li.find('div.error');

                // 避免重复创建
                if (!$error.length) {
                    $error = $('<div class="error"></div>').appendTo($li);
                }

                $error.text('上传失败');
            });

            // 完成上传完了，成功或者失败，先删除进度条。
            uploader.on('uploadComplete', function (file) {
                $('#' + file.id).find('.progress').remove();
            });
        });
    </script>
@stop



