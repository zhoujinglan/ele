# ELE点餐平台

## 项目介绍

整个系统分为三个不同的网站，分别是 

- 平台：网站管理者 
- 商户：入住平台的餐馆 
- 用户：订餐的用户

## Day01

### 开发任务

#### 平台端 

- 商家分类管理 
- 商家管理 
- 商家审核

#### 商户端 

- 商家注册

#### 要求 

- 商家注册时，同步填写商家信息，商家账号和密码 
- 商家注册后，需要平台审核通过，账号才能使用 
- 平台可以直接添加商家信息和账户，默认已审核通过

### 实现步骤

1. composer create-project --prefer-dist laravel/laravel ele "5.5.*" -vvv
2. 设置虚拟主机  三个域名
3. 把基本配置 
4. 建立数据库ele
5. 配置.env文件 数据库配好
6. 配置语言包
7. 数据迁移
8. 创建表   php artisan make:model Models/ShopCategory -m
9. 准备好基础模板

10. 创建 控制器 php artisan make:controller Admin/ShopCategoryController
11. 创建视图 视图也要分模块
12. 路由需要分组
```$xslt
<VirtualHost *:80>
    DocumentRoot "D:\lv\ele\public"
    ServerName www.ele.com
    ServerAlias  www.admin.ele.com  www.shop.ele.com
  <Directory "D:\lv\ele\public">
      Options Indexes FollowSymLinks ExecCGI 
      AllowOverride All
      Order allow,deny
      Allow from all
      Require all granted   	
  </Directory>
</VirtualHost>
```
13.用户注册

14.用户登录

15.登录完成  进入后台 申请店铺

16.平台 

平台需要创建店铺分类  申请处理

平台可以直接添加商家信息和账户，默认已审核通过

### day2
1.完善第一天的 判断是有有店铺  可以不用 登录时已经判断
```$xslt
  public function index(  ){
        //$id=Auth::user();
        //dd($id);
        //if($id=)
        //判断是否拥有店铺
        if(Auth::user()->id ===null){
            //跳转到添加店铺
            return redirect()->route("shop.apply")->with("warning","你还没有创建店铺");
        }

        return view("shop.index.index");
    }
```

2.在平台显示所有用户信息

3. 要用事务保证同时删除用户和店铺，删除图片  未
```$xslt
 //删除商户信息
    public function delUser($id){
        
        //应用事务  同时删除
        DB::transaction(function () use ($id) {
          
            User::find($id)->delete();
            Shop::where("user_id",$id)->delete();

        });
        return redirect()->route("user.list")->with("success","删除成功");

    }
```
4.平台 管理员登录和注销功能，修改个人密码(参考微信修改密码功能)
```$xslt
 //修改管理员资料
    public function edit(Request $request,$id){
        //
        $admin=Admin::find($id);
        if($request->isMethod("post")){
            /*
           $data= $this->validate($request,[
                "password"=>"required|confirmed",
                "old_password"=>"required",
            ]);
           */
            //得到当前用户对象
           // $admin = Auth::guard('admin')->user();
            //dd($admin);
            $oldPassword = $request->post('old_password');
            //判断老密码是否正确
            //dd(Hash::check($oldPassword, $admin['password']));返回true或者false
            if (Hash::check($oldPassword, $admin['password'])) {
                //如果老密码正确 设置新密码
                $admin['password'] = Hash::make($request->post('password'));
                // 保存修改
                $admin->save();
                //跳转
                return redirect()->route('admin.index')->with("success", "修改密码成功");
            }
            //4.老密码不正确
            return back()->with("danger", "旧密码不正确");

        }
        //显示视图
        return view("admin.admin.edit",compact("admin"));
    }
```
5.商户账号管理，重置商户密码
```$xslt
 public function editUser($id){
        $user= User::find($id);
        //dd($user);
        $password= Hash::make(111111);
        //dd($password);
        $user['password']=$password;
        $user->save();
        return redirect()->route("user.list")->with("success","重置密码完成111111");

    }
```
6.商户端：商户登录和注销功能，修改个人密码
用到验证码
```$xslt
 //修改个人密码
    public function edit(Request $request,$id){
        //
        $user= User::find($id);

        if($request->isMethod("post")){

            $this->validate( $request, [
                "password"     => "required|confirmed",
                "old_password" => "required",
            ] );

            $oldPassword = $request->post( 'old_password' );
            //判断老密码是否正确
            //dd(Hash::check($oldPassword, $admin['password']));返回true或者false
            if( Hash::check( $oldPassword, $user['password'] ) ){
                //如果老密码正确 设置新密码
                $user['password'] = Hash::make( $request->post( 'password' ) );
                // 保存修改
                $user->save();
                //跳转
                return redirect()->route( 'index' )->with( "success", "修改密码成功" );
            }

            //4.老密码不正确
            return back()->with( "danger", "旧密码不正确" );
        }
        //显示视图
        return view("shop.user.edit",compact("user"));

    }
```
7.商户登录正常登录，登录之后判断店铺状态是否为1，不为1不能做任何操作

```$xslt
    //登录
       public function login(Request $request){
        
           //判断接收方式
           if($request->isMethod("post")){
               //验证信息
               $data = $this->validate($request, [
                   'name' => "required",
                   'password' => "required"
               ]);
               //验证密码是否正确
               if(Auth::attempt($data)){
                   //密码正确
                   //判断商铺状态
                   $shop = Auth::user()->shop;//shop是user模型里的一种方法
                   if($shop){//如果店铺有 看状态
                       //状态就判断-1 0
                       switch($shop->status){
                           case -1:
                               //禁用 退登录
                               Auth::logout();
                               return back()->withInput()->with("danger","你的店铺已禁用");
                               break;
                           case 0:
                               //未审核 退登录
                               Auth::logout();
                               return back()->withInput()->with("danger","你的店铺还未通过审核");
                               break;
                       }
                   }else{
   
   
                      // 没有店铺 申请
                       return redirect()->route("shop.apply")->with("success","你还没有店铺 请先申请");
                   }
                   //上面的条件都满足 登录首页
                   return redirect()->route("index")->with("success","登录成功");
   
               }else{
                   //密码不正确
                   return redirect()->back()->withInput()->with("danger","账号或密码错误");
   
               }
           }
   
               //显示视图
           return view("shop.user.login");
       }
```

### 踩的坑
1.
shop下边的shopcontroller继承有问题  继承出错导致权限调到admin里面
2.用户申请店铺之后回到登录首页  并注销登录


### day3
## DAY03

### 开发任务

商户端 

- 菜品分类管理 
- 菜品管理 
  要求 
- 一个商户只能有且仅有一个默认菜品分类 
- 只能删除空菜品分类 
- 必须登录才能管理商户后台（使用中间件实现）  已完成
- 可以按菜品分类显示该分类下的菜品列表 
- 可以根据条件（按菜品名称和价格区间）搜索菜品

具体工作
1.完善day2  平台添加一个添加商户的功能
2.完善 平台 有店铺的分类不能删除
```$xslt
增加一个判断
 //得到当前分类对应的店铺数
        $num=Shop::where('shop_category_id',$shop->id)->count();
       // dd($num);
        //判断当前分类店铺数
        if ($num){
            //回跳
            return  back()->with("danger","有店铺 的分类不能删除");
        }
```
3.商户 添加一个菜品分类
```$xslt
 public function add(Request $request){
       //判断接收方式
        if($request->isMethod("post")){
            $data= $this->validate( $request, [
                'name'=> 'required |unique:menu_categories',
                "type_accumulation" => "required",
                "description" => "required",
                "is_selected" => "required",
            ] );
            $id=Auth::user()->id;
            //dd($id);
            $data['shop_id']=$id;
            //添加
            MenuCategory::create($data);
            //跳转
            return redirect()->route("menu_cate.index")->with("success","添加成功");

        }

        //显示视图
        return view("shop.menu_cate.add");
    }
```
3.菜品管理 

注意分类表与菜品两个表对应关系  菜品属于哪个店铺
```$xslt

class Shop extends Model
{
    //
    protected $fillable = [
        'shop_name', 'shop_img', 'shop_rating','brand','on_time','fengniao','bao','piao','zhun','start_send','send_cost','notice','discount','status','shop_category_id','user_id'
    ];

    //读取分类的名字
    public function shop_category(){
        return $this->belongsTo(ShopCategory::class,"shop_category_id");
    }

    //读取用户的名字
    public function user(){
        return $this->belongsTo(User::class,"user_id");
    }
}

```
4.搜索
```$xslt
视图
  <form class="form-inline pull-right" method="get">
                <div class="form-group">

                    <select  name="class_id"  class="form-control">
                        <option value="">请选择商品分类</option>
                        @foreach($cates as $cate)
                            <option value="{{$cate->id}}">{{$cate->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">

                    <input type="text" class="form-control" size="5"  placeholder="最低价" name="minPrice">
                </div>
                -
                <div class="form-group">

                    <input type="text" class="form-control" size="5"  placeholder="最高价" name="maxPrice">
                </div>
                <div class="form-group">

                    <input type="text" class="form-control" size="10"  placeholder="关键字" name="keyword">
                </div>

                <button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-search"></i></button>
            </form>
```
```$xslt
php代码
 public function index(Request $request){
        //分页
        //接收参数
        $url =$request->query();

        $cateId = $request->get('category_id');
       //dd($cateId);
        $minPrice = $request->get('minPrice');
        $maxPrice = $request->get('maxPrice');
        $keyword = $request->get('keyword');
        //得到所有并要有分页

       // $query=Menu::orderBy("id");
         $query=Menu::orderBy("id");
        if ($keyword!==null){

            $query->where("goods_name","like","%{$keyword}%");
        }
        if($minPrice !== null  ){
            $query->where("goods_price",">=",$minPrice);
        }
        if($maxPrice !== null){
            $query->where("goods_price","<=",$maxPrice);
        }

        if ($cateId!==null){
            $query->where("category_id",$cateId);
        }

        //$menus =Menu::all();
        $menus =$query->where("shop_id",Auth::user()->shop->id)->paginate(3);

        // 获得菜品分类
        $cates=MenuCategory::all();
      //显示视图
        return view("shop.menu.index",compact("menus","cates","url"));
    }
```
## 疑问
一个商户只能有且仅有一个默认菜品分类 

应该先查出分类表中被选中的个数
判断is_selected>1 则不能添加  或者修改
```$xslt
 if($data['is_selected'] == 1){
                 MenuCategory::where("shop_id",$cate->shop_id)->update(['is_selected'=>0]);
                // dd($a);
            }
```

# day4
### 开发任务
优化 - 将网站图片上传到阿里云OSS对象存储服务，以减轻服务器压力(https://github.com/jacobcyl/Aliyun-oss-storage) - 使用webuploder图片上传插件，提升用户上传图片体验

平台 - 平台活动管理（活动列表可按条件筛选 未开始/进行中/已结束 的活动） - 活动内容使用ueditor内容编辑器(https://github.com/overtrue/laravel-ueditor)

商户端 - 查看平台活动（活动列表和活动详情） - 活动列表不显示已结束的活动
### 实现步骤
######阿里云oss云存储
1.登录
2.新建 bucket 取名 域名 标准存储 公共读
3.点击用户图像---》accesskeys--->继续使用accsskeys--->添加accesskeys--->拿到access_id和access_key
AccessKeyId:
LTAIS3IkhTzPg026

AccessKeySecret:
8HfRZAMVb6KervXAmze0fQ7hTSbA9O
4.执行命令  安装 ali-oss插件
```apacheconfig
composer require jacobcyl/ali-oss-storage -vvv
```
5.修改 config/filesystems.php 添加如何代码
```
  'oss' => [
            'driver'        => 'oss',
            'access_id'     => env("ALIYUNU_ACCESS_ID"),//账号
            'access_key'    => env("ALIYUNU_ACCESS_KEY"),//密钥
            'bucket'        => env("ALIYUNU_OSS_BUCKET"),//空间名称
            'endpoint'      =>env("ALIYUNU_OSS_ENDPOINT"), // OSS 外网节点或自定义外部域名
        ],

```
6. 配置文件 .env添加
```
FILESYSTEM_DRIVER=oss
ALIYUN_OSS_URL=http://elezjl.oss-cn-shenzhen.aliyuncs.com/
ALIYUNU_ACCESS_ID=LTAIS3IkhTzPg026
ALIYUNU_ACCESS_KEY=8HfRZAMVb6KervXAmze0fQ7hTSbA9O
ALIYUNU_OSS_BUCKET=elezjl
ALIYUNU_OSS_ENDPOINT=oss-cn-shenzhen.aliyuncs.com
```
7.获得图片
```php
<td><img src="{{env("ALIYUN_OSS_URL").$menu->goods_img}}?x-oss-process=image/resize,m_fill,w_80,h_80"></td>
```
## webuploader 使用
1.下载
https://github.com/fex-team/webuploader/releases/download/0.1.5/webuploader-0.1.5.zip 解压到public 下 webuploader 
2.在layouts下main文件引入css和js
```php
头部
 <link rel="stylesheet" type="text/css" href="/webuploader/webuploader.css">
 尾部  body结束之前
 <script type="text/javascript" src="/webuploader/webuploader.js"></script>
 @yield("js")//占一个位置写js代码
 
```
3.在添加的视图中
html添加
```html
   <div class="form-group">
              <label class="col-sm-2 control-label">图片</label>
  
              <input type="hidden" name="goods_img" value="" id="goods_img">
              <!--dom结构部分-->
              <div id="uploader-demo" class="col-sm-10">
                  <!--用来存放item-->
                  <div id="fileList" class="uploader-list"></div>
                  <div id="filePicker">选择图片</div>
              </div>
  
          </div>


```
js
```php
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
                server: '{{route("menu_cate.upload")}}',

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

                $("#logo").val(data.url);
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
```
4.创建路由 方法
```php
添加的方法
 public function add(Request $request){
       //判断接收方式
        $id=Auth::id();
        if($request->isMethod("post")){
            $this->validate( $request, [
                'goods_name'=> 'required |unique:menus',
                "category_id" => "required",
                "description" => "required",
                "goods_price" => "required",
                "status" => "required",

            ] );
            $data=$request->post();
            $data['shop_id']=Auth::user()->shop->id;

           Menu::create($data);
            //跳转
            return redirect()->route("menu.index")->with("success","添加成功");

        }

        //显示视图
        //查询所有分类
        $cates = MenuCategory::all();
        return view("shop.menu.add",compact("cates"));

    }
  
     //添加图片的方法
        public function upload(Request $request){
    
            //上传处理
            $file=$request->file("file");//内部的文件  没有在html中显示  固定写法
            if($file){
                //有文件进行上传
                $url = $file->store("menu");
                //得到真实地址  把http加载进去
                $url = Storage::url($url);
                $data['url']=$url;
                return $data;
            }
    
        }
```
图片修改
```php
public function edit(Request $request,$id){
        $menu=Menu::find($id);
       // dd($menu);
        //判断接收方式
        if($request->isMethod("post")){
            $this->validate( $request, [
                //'goods_name'=> 'required |unique:menus,goods_name'.$id,
                'goods_name'=>[
                  'required',
                  Rule::unique("menus")->ignore($id)
                ],
                "category_id" => "required",
                "goods_price" => "required",
                "status" => "required",

            ] );

            $data =$request->post();
            //删除原来的图片
           // dd($data);
            Storage::disk("oss")->delete($menu->goods_img);

            $menu->update($data);
            //修改成功跳转
            return redirect()->route("menu.index")->with("success","修改成功");
        }
        //回显

        $cates=MenuCategory::all();
        //显示视图
        return view("shop.menu.edit",compact("menu","cates"));
    }
```
5.商户端添加活动
 活动内容使用ueditor内容编辑器(https://github.com/overtrue/laravel-ueditor)

 步骤
 1）composer require "overtrue/laravel-ueditor:~1.0"

 2）添加下面一行到 config/app.php 中 providers 部分
 ```php
Overtrue\LaravelUEditor\UEditorServiceProvider::class,
 ```
3)发布配置文件与资源
```php
php artisan vendor:publish
然后找到 
Overtrue\LaravelUEditor\UEditorServiceProvider 相关的数字

```
4）模板引入编辑器 
在header部分引入
```php
@include('vendor.ueditor.assets')
```
在尾部 body结束之前 
```php
<script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js"></script>
@yield("js")//添加这个
```
5）编辑器的初始化 使用
```html
html地方
        <div class="form-group">
            <label for="start_send" class="col-sm-2 control-label">活动详情</label>
            <div class="col-sm-10">
            <script id="container" name="content" type="text/plain"></script>
            </div>
        </div>
```
```php
@section("js")
    <script type="text/javascript">
        var ue = UE.getEditor('container');
        ue.ready(function() {
            ue.execCommand('serverparam', '_token', '{{ csrf_token() }}'); // 设置 CSRF token.
        });
    </script>

    @endsection
```
6.活动显示  活动列表不显示已结束的活动
```php
 $activities=Activity::where("end_time",">=",date('Y-m-d H:i:s', time()))->get();
```
7.搜索  平台活动管理（活动列表可按条件筛选 未开始/进行中/已结束 的活动）
```php
  public function index(Request $request ){
        //$activities=Activity::where("end_time",">=",date('Y-m-d H:i:s', time()))->get();
       // dd($activities[0]->start_time);
        //dd(date('Y-m-d H:i:s', time()));//转成当前时间
        //搜索
       $url = $request->query();
       $time = $request->get("time");
       $content = $request->get("keyword");
       //拼接查询条件
        $query = Activity::orderBy("id");
        //得到当前时间
        $nowTime=date('Y-m-d H:i:s', time());
        //判断时间  1 进行 2 结束 3 未开始
        if( $time == 1 ){
            $query->where("start_time","<=",$nowTime)->where("end_time",">",$nowTime);
        }
        if($time == 2){
            $query->where("end_time","<",$nowTime);
        }
        if($time == 3){
            $query->where("start_time",">",$nowTime);
        }
        //内容搜索
        if($content !== null){
            $query->where("title","like","%{$content}%")->orWhere("content","like","%{$content}%");
        }

        $activities = $query->paginate(2);

        //$activities =Activity::all();
        //引入视图
        return view("admin.activity.index",compact("activities","url"));

    }
```
# 坑 编辑时的唯一问题
 ```php

 ```
# day5 api接口
开发任务
接口开发

商家列表接口(支持商家搜索)
获取指定商家接口

1.先把前端的首页引入进来 注意里面的配置
```php
 <link href=./static/css/app.d40081f78a711e3486e27f787eed3c1f.css rel=stylesheet>
 <script type=text/javascript src=./api.js></script>
```
2.routes 下的api.php 文件创造路由
```php

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
}); 

Route::get("shop/index","Api\ShopController@index");
Route::get("shop/detail","Api\ShopController@detail");
```
3.创造控制器 Api/shopcontroller
```php
class ShopController extends Controller
{
    //获得所有店铺
    public function index(  ){
        $shops = Shop::where("status",1)->get();
        //dd($shops->toArray());
        //把距离和送达时间追加上去
        foreach($shops as $k=>$v){
            $shops[$k]->distance=rand(1000,5000);
            $shops[$k]->estimate_time=ceil($shops[$k]->distance/rand(100,150));
        }
      return $shops;
    }

    //显示指定商家的店铺
    public function detail(  ){
        $id=request()->get('id');
        //dd($id);
        //通过id找到特定的一条
        $shop = Shop::find($id);

        //dd($shop);
        //$shop->shop_img=env("ALIYUN_OSS_URL").$shop->shop_img;
           // dd($shop->shop_img);
        //追加总评分
        $shop->service_code=4.5;
        //用户评论
        $shop->evaluate=[
          ["user_id"=> 12344,
                "username"=> "w******k",
                "user_img"=> "http://www.homework.com/images/slider-pic4.jpeg",
                "time"=> "2017-2-22",
                "evaluate_code"=>1,
                "send_time"=> 30,
                "evaluate_details"=> "不怎么好吃"],
          [
              "user_id"=> 12344,
                "username"=> "w******k",
                "user_img"=> "http://www.homework.com/images/slider-pic4.jpeg",
                "time"=> "2017-2-22",
                "evaluate_code"=> 4.5,
                "send_time"=> 30,
                "evaluate_details"=> "很好吃"
          ],
        ];
        //菜品分类
        $cates = MenuCategory::where("shop_id",$id)->get();
       //
        //遍历出当前分类有哪些商品
        foreach($cates as $k => $cate){

               // dd($cates[$k]);
           // dd($data[0]->goods_img);
            $cates[$k]->goods_list =$cate->goodsList;//goodsList是方法
            foreach ($cates[$k]->goods_list as $i =>$v){
                ($cates[$k]->goods_list)[$i]->goods_img=env("ALIYUN_OSS_URL"). ($cates[$k]->goods_list)[$i]->goods_img;

            }


        }

        $shop->commodity=$cates;
        //dd($shop);
        return $shop;

    }
}
```
4.在public下的api.js写接口路由
```php
  // 获得商家列表接口
  businessList: '/api/shop/index',
  // 获得指定商家接口
  business: '/api/shop/detail',
```
# Day06
开发任务
接口开发

用户注册
用户登录
忘记密码
发送短信 要求
创建会员表
短信验证码发送成功后,保存到redis,并设置有效期5分钟
用户注册时,从redis取出验证码进行验证
### 实现步骤
1.短信验证

参考 https://packagist.org/packages/mrgoon/aliyun-sms 使用非Laravel框架方法

安装
```php
 composer require mrgoon/aliyun-sms dev-master
​```php
采用非 laravel 框架的使用方法
​```php

     $config = [
            'access_key' => env("ALIYUNU_ACCESS_ID"),//appid
            'access_secret' =>env("ALIYUNU_ACCESS_KEY"),//阿里云appkey
            'sign_name' => env('ALIYUN_SIGN_NAME'),//签名
        ];

        $sms = new AliSms();
        $response = $sms->sendSms($tel, 'SMS_149422431', ['code'=> $code], $config);
```
3.用redis保存验证码  参考文档
https://laravel-china.org/docs/laravel/5.5/redis/1331

步骤
3.1 Composer 安装 predis/predis 扩展包
```php
composer require predis/predis
```
  3.2把验证码保存起来（redis  文件保存）
        Redis::set("tel_".$tel,$code);//先保存
        Redis::expire('tel_'.$tel,60*10);//设置多久时间失效  验证码重发
        
  # Day07
   开发任务
   接口开发

   用户地址管理相关接口
   ```php
   用到手动验证 电话号码用正则验证
   $validate = Validator::make($data,[
               'name'=>"required | unique:addresses",
               'detail_address'=>"required",
               'provence'=>"required",
               'city'=>'required',
               'area'=>'required',
               'user_id'=>"required",
   
               "tel"=>[
                   'required',
                   'regex:/^0?(13|14|15|16|17|18|19)[0-9]{9}$/',//电话号码的正则表达
                   'unique:members'
   
               ],//电话号码用正则验证
           ]);

   ```
   购物车相关接口  
    显示 商品的遍历相对较为复杂
    ```php
     public function index(  ){
    
            //获得user_id
            $user_id = request()->get('user_id');
           // dd($user_id);
            //显示购物车
           $carts= Cart::where("user_id",$user_id)->get();
           //声明一个总价变量
            $totalCost =0;
            //声明一个存放购物内容的变量
            $goodList=[];
    
           //循环遍历出来
            foreach($carts as $k =>$v){
                //先把菜单读取出来
                $good = Menu::where('id',$v->goods_id)->first(['id as goods_id','goods_name', 'goods_img', 'goods_price']);
                //数量
                $good->goods_img=env("ALIYUN_OSS_URL").$good->goods_img;
                $good->amount = $v->amount;
                //总价
                $totalCost=$totalCost+$good->amount*$good->goods_price;
                //把购物内容保存起来
                $goodList[]=$good;
    
            }
            //返回数据
            return[
                'goods_list'=>$goodList,
                "totalCost"=>$totalCost,
            ];


    
        }
    ```
 ##### 添加之前注意清空之前的购物列表
 # Day08
 开发任务
 接口开发

 订单接口(使用事务保证订单和订单商品表同时写入成功)


# Day09
 开发任务
 商户端

 订单管理[订单列表,查看订单,取消订单,发货]

 订单量统计[按日统计,按月统计,累计]（每日、每月、总计）

 菜品销量统计[按日统计,按月统计,累计]（每日、每月、总计）

 平台

 订单量统计[按商家分别统计和整体统计]（每日、每月、总计）

 菜品销量统计[按商家分别统计和整体统计]（每日、每月、总计）

 会员管理[会员列表,查询会员,查看会员信息,禁用会员账号]
  ### 实现步骤
  商户端 按日统计
  ```php
  #1.找到当前店铺的所有订单id
  $shop_id=Oreder::

  ```
# Day10
#### 开发任务
平台

权限管理
角色管理[添加角色时,给角色关联权限]
管理员管理[添加和修改管理员时,修改管理员的角色]​        

#### 实现步骤

1.composer安装

```
composer require spatie/laravel-permission -vvv
```

2.生成数据迁移

```
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider" --tag="migrations"

# 可以在迁移的权限表中添加intro字段
```

3.执行数据迁移

```
php artisan migrate
```

4.生成配置文件

```
 php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider" --tag="config"
```

5.Admin模型当中添加门卫

```
class Admin extends Authenticatable
{

    use HasRoles;
    protected $guard_name = 'admin'; // 使用任何你想要的守卫
    protected $fillable=["username","password"];

}
```

6.在PermissionController控制器添加权限

```
 //添加权限
    public function add(Request $request){
        //判断接收方法
        if($request->isMethod("post")){
            //验证
            $this->validate($request,[
                'name'=>"required",
                'intro'=>"required",

            ]);
            $data = $request->post();
            $data['guard_name']="admin";
            //dd($data);
            Permission::create($data);

        }
        //引入视图
        return view("admin.per.add");
    }
```

7.在RoleController控制器中添加角色         

```
//添加角色
    public function add(Request $request ){
        //判断接收方式
        if($request->isMethod('post')){
            $this->validate($request,[
                'name'=>"required"
            ]);
            //接收数据  并处理
            $pers =$request->post('pers');//获得权限
            //dd($pers);
            //添加角色
            $role = Role::create([
                'name'=>$request->post('name'),
                'guard_name'=>'admin',
                                 ]);
            //同步角色
            if($pers){//判断是否添加了权限
                $role->syncPermissions($pers);
            }

        }
        //显示视图
        //得到所有权限
        $pers =Permission::all();
        return view("admin.role.add",compact('pers'));
    }
```

8.给用户指定角色

```
 public function add(Request $request ){
        //判断接收方式
        if($request->isMethod('post')){
            $this->validate($request,[
                'name'=>"required"
            ]);
            //接收数据  并处理
            $pers =$request->post('pers');//获得权限
            //dd($pers);
            //添加角色
            $role = Role::create([
                'name'=>$request->post('name'),
                'guard_name'=>'admin',
                                 ]);
            //同步角色
            if($pers){
                $role->syncPermissions($pers);
            }

        }
        //显示视图
        //得到所有权限
        $pers =Permission::all();
        return view("admin.role.add",compact('pers'));
    }
```

9.判断权限 在E:\web\ele\app\Http\Controllers\Admin\BaseController.php 添加如下

```
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Mockery\Matcher\Closure;

public function __construct()
    {
        //添加中间件
        $this->middleware("auth:admin")->except(['login']);

        //有没有权限
        $this->middleware(function($request,\Closure $next){
            $route =Route::currentRouteName();

            //设置白名单
            $allow=[
                'admin.login',
                'admin.logout'
            ];
            //要保证在白名单 并且有权限 id==1
            if(!in_array($route,$allow) && !Auth::guard("admin")->user()->can($route) && Auth::guard("admin")->id()!=1){
                exit(view("admin.fuck"));
            }
            return $next($request);
        });

    }
```

10.创建admin.fuck视图

```
@extends("admin.layouts.main")

@section("content")
    没有权限
@endsection
```

11.获得角色的权限

```
<td>{{ json_encode($role->permissions()->pluck('intro'),JSON_UNESCAPED_UNICODE)}}
                </td>
  或者
 {{str_replace(['[',']','"'],'', json_encode($role->permissions()->pluck('intro'),JSON_UNESCAPED_UNICODE))}}
               
//取出当前角色所拥有的所有权限
   $role->permissions();
```

12.获得当前用户的权限

```
<td>{{json_encode($roles = $admin->getRoleNames(),JSON_UNESCAPED_UNICODE)}}</td>

 //取出当前用户所拥有的角色
   $roles = $admin->getRoleNames(); // 返回一个集合
```

13.其他用法

```
 //判断当前角色有没有当前权限
   $role->hasPermissionTo('edit articles');
   //判断当前用户有没有权限
   $admin->hasRole('角色名')
```

14.权限回显

role控制器中   

```
 public function edit( Request $request,$id ){
        //
        $role =Role::find($id);
        //dd($role);
        $rol =  $role->permissions()->pluck('id')->toArray();//这是很重要的 读取有的权限
        //dd($rol);
        //判断接收


            if($request->isMethod('post')){
                $this->validate( $request, [
                    'name' => "required"
                ] );
                //接收数据  并处理
                $pers = $request->post( 'pers' );//获得权限
                //dd($pers);
                //添加角色
                $role->update( [
                                   'name'       => $request->post( 'name' ),
                                   'guard_name' => 'admin',
                               ] );
                //同步角色
                if( $pers ){
                    $role->syncPermissions( $pers );
                }

            return redirect()->route("role.index")->with("success","修改成功");
        }

        //获得
        $pers =Permission::all();
        return view("admin.role.edit",compact("pers","role","rol"));

    }
```

html

```
  <form action="" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="form-group">
            <label>角色名称</label>
            <input type="text" class="form-control"  placeholder="名称" name="name" value="{{$role->name}}">
        </div>


        <div class="form-group">
            <label>权限</label>
            @foreach($pers as $per)
                <input type="checkbox" name="pers[]" value="{{$per->id}}" {{in_array($per->id,$rol)?'checked':""}}>
                {{$per->intro}}
            @endforeach

        </div>


        <button type="submit" class="btn btn-default">修改</button>
    </form>
```

15.角色回显

php代码

```
 public function update(Request $request,$id){
        //、判断接收方式
        $admin =Admin::find($id);
        $rol = $admin->getRoleNames()->toArray();//当前的角色

        if($request->isMethod("post")){
            $this->validate($request, [
                'name'=>"required",
                'email' => "required"
            ]);
            // dd($request->post('per'));
            //接收参数
            $data = $request->post();

            $admin ->update($data);//添加用户

            //给用户添加角色  角色同步
            $admin->syncRoles($request->post('role'));
            //跳转
            return redirect()->route("admin.index")->with("success","修改成功");

        }
        //视图显示
        //查询所有角色
        $roles =Role::all();
        return view("admin.admin.update",compact("admin","rol","roles"));
    }
```

html

````
<form action="" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="form-group">
            <label>名称</label>
            <input type="text" class="form-control"  placeholder="名称" name="name" value="{{$admin->name}}">
        </div>

        <div class="form-group">
            <label>邮箱</label>
            <input type="email" class="form-control"  placeholder="邮箱" name="email" value="{{$admin->email}}">
        </div>


        <div class="form-group">
            <label>权限</label>
            @foreach($roles as $role)
                <input type="checkbox" name="role[]" value="{{$role->id}}" {{in_array($role->name,$rol)?'checked':""}}>
                {{$role->name}}
            @endforeach

        </div>


        <button type="submit" class="btn btn-default">修改</button>
    </form>
````

# day11

### 开发任务

#### 平台

- 导航菜单管理
- 根据权限显示菜单
- 配置RBAC权限管理

#### 商家

- 发送邮件(商家审核通过,以及有订单产生时,给商家发送邮件提醒) 用户
- 下单成功时,给用户发送手机短信提醒

#### 实现步骤

1.导航菜单管理

```
    //添加
public function add(Request $request  ){

    if($request->isMethod('post')){
        //验证
        //接收
        $data =$request->post();
        //添加入库
        Nav::create($data);
        session("success","添加成功");
    }
    //获取所有路由
    //先声明一个空数组来保存路由名字
    $urls=[];
    //得到所有路由

    $routes=Route::getRoutes();
    //循环遍历所有路由
    foreach($routes as $route){
        if(isset($route->action["namespace"]) && $route->action["namespace"]=="App\Http\Controllers\Admin"){
            //读取路由名 并保存
            $urls[]=$route->action['as'];
        }
    }
    //从数据读取已经存在的
    $pers =Nav::pluck("url")->toArray();
    //去掉已经存在的路由
    $urls =array_diff($urls,$pers);
    //显示视图
    return view("admin.nav.add",compact("urls"));
}
```

2.html页面显示遍历菜单

```

                    @foreach(\App\Models\Nav::where("pid",0)->get() as $k1=>$v1)
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{$v1->name}} <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            @foreach(\App\Models\Nav::where("pid",$v1->id)->get() as $k2=>$v2)
                            <li><a href="{{route($v2->url)}}">{{$v2->name}}</a></li>
                            @endforeach
                        </ul>
                    </li>
                    @endforeach


```

3.发送邮件

```
邮件发送实在通过审核后 所以这个方法直接写在店铺审核中、
 //审核
    public function audit($id){
        $one = Shop::find($id);
       // dd($one);
        $user =User::where("id",$one->user_id)->first();
        //dd($user->email);
       //判断状态
          //dd($data);
        if( DB::update( 'update shops set status = 1 where id =:id', [ $id]) ){
            //发邮箱通知
            $shopName=$one->shop_name;
            $to =$user->email;//收件人
            $subject = $shopName.' 审核通知';//邮件标题
            \Illuminate\Support\Facades\Mail::send(
                'emails.shop',//视图
                compact("shopName"),//传递给视图的参数
                function ($message) use($to, $subject) {
                    $message->to($to)->subject($subject);
                }
            );

        }
            //跳转
            return redirect()->route("shop.list");


        //显示视图
        //return view("admin.shop.audit",compact("one"));

    }
```

D:\lv\ele\resources\views\emails\shop.blade.php 创建视图  <-这一步很重要

```
<p>
    你的店铺：{{$shopName}} 已通过审核，可以登录了
</p>
```

4.自己更改了需求  支付成功后发送短信

```
写在接口控制器下
public function pay(Request $request){
        //得到订单id
        $id =$request->post("id");
       // dd($id);
        //得到订单
        $order=Order::find($id);

        //得到用户
        $member=Member::find($order->user_id);
        //判断用户余额是否足够
        if($order->total > $member->money){
            return [
                'status'=>"false",
                'message'=>"余额不足 请换个支付",
            ];
        }

        //余额充足 请支付  减去订单金额
        DB::transaction(function() use($member,$order){
            $member->money=$member->money -$order->total;
            $member->save();
            //更改订单状态
            $order->status=1;
            $order->save();
        });
        //支付成功
        //得到电话号码
        $tel =$order->tel;
        //         dd($tel);
        //        $tel=18602302240;
        //得到店铺id
        $shop_id = $order->shop_id;
        //得到店铺信息
        $shop=Shop::where("id",$shop_id)->first();
        //dd($shop->shop_name);
        //发短信
        $code="最让你舍不得的平台eles的".$shop->shop_name;
        //        dd($code);
        //4. 把验证码发给手机 用到阿里云短信服务
        $config = [
            'access_key' => env("ALIYUNU_ACCESS_ID"),
            'access_secret' => env("ALIYUNU_ACCESS_KEY"),
            'sign_name' => '个人生活记录',
        ];
        $sms=New AliSms();
        //        dd($tel);
        $response = $sms->sendSms($tel, "SMS_150575336", ['name'=> $code], $config);
        //        dd($response);
        //返回成功信息
        return [
            'status'=>"true",
            'message'=>"支付成功",
        ];

    }
```
##### 根据权限显示菜单
实现步骤


