# ELE点餐平台

## 项目介绍

整个系统分为三个不同的网站，分别是 

- 平台：网站管理者 
- 商户：入住平台的餐馆 
- 用户：订餐的用户

# Day01

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

# day2

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

# day3

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
# day5 

api接口

##### 开发任务
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
#### 开发任务
 接口开发

 订单接口(使用事务保证订单和订单商品表同时写入成功)


# Day09
#### 开发任务

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



```

```



实现步骤
开始抽奖  抽奖
```php
 //开奖活动
    public function open(Request $request,$id){
        //通过当前活动id 找出参与活动的用户 转化成数组
        $userId=EventUser::where("event_id",$id)->pluck("user_id")->toArray();
        //dd($userId);
        //将用户id打乱
        shuffle($userId);//返回ture
        //dd($user);
        //找出当前的活动奖品 并随机打乱
        $prizes =EventPrize::where("event_id",$id)->get()->shuffle();
      // dd($prizes);
        //把奖品给对应的user_id
        foreach($prizes as $k=>$prize){
            //dd($prize);
            $prize->user_id=$userId[$k];
           // dd($prize->user_id);
            $one =User::find( $prize->user_id);
            $name=$one->name;
            $to =$one->email;//收件人
            $subject = '中奖通知';//邮件标题
            \Illuminate\Support\Facades\Mail::send(
                'emails.open',//视图
                compact("name"),//传递给视图的参数
                function ($message) use($to, $subject) {
                    $message->to($to)->subject($subject);
                }
            );
            //保存修改
            $prize->save();
        }
        //修改活动状态
        $event=Event::find($id);
        $event->is_prize=1;
        $event->save();
        return redirect()->back()->with("success","开奖完成");
    }
```
记得创建视图 在email下创建open

商户报名抽奖
```php
public function join($id  ){
        $event=Event::find($id);
       // dd($event);
        $num=EventUser::where('event_id',$event->id)->count();
        //dd($num);
        $user=EventUser::where('event_id',$event->id)->first();
        //dd($user);
        if($num > $event->num ){
                return back()->with("success","报名已满");
        }

        $data['user_id']=Auth::user()->id;
        //dd( $data['user_id']);
        $data['event_id']=$id;
        if( $data['user_id'] == $user->user_id){
            return back()->with("warning","你已报名");
        }
        EventUser::create($data);
        return back()->with("success","报名成功 等待开奖");

    }
```

# day12

#### 项目上线

#### 上线步骤

1. 解析域名 www @ * =====服务器IP A记录

2. 登录服务器 执行命令安装宝塔

   ```
   yum install -y wget && wget -O install.sh http://download.bt.cn/install/install.sh && sh install.sh
   ```

3. 登录宝塔管理网址

   ```
   Bt-Panel: http://132.232.143.76:8888
   username: *****
   password: *****
   ```

4. 安装Lamp环境

   PHP版本和MYSQL版本最好和本地开发环境保持一致


5.用SSH管理工具进入到/www/wwwroot 目录下 执行如下命令

```
git clone https://github.com/zhoujinglan/ele.git work
```

6.在宝塔中添加一个网站  设置三个域名

>运行目录public
>
>去掉跨站脚本攻击
>
>重启PHP
>
>网站根目录选择work

7.composer设置重中国镜像  并给自己升级

```
composer config -g repo.packagist composer https://packagist.laravel-china.org
composer self-update //提示升级才用
```

8.重新安装composer

>在宝塔界面软件管理 php-7.0操作以下
>
>安装 fileinfo 扩展
>
>删除 proc_open 函数
>
>proc_get_status()

```
poser install 
```

9.在宝塔中新建.env空白文件 并把本地.env文件内容复制去  或者

```
cp .env.example .env
php artisan key:generate //这样的文件会少一些之前设置的东西
```

并把数据库  用户名 密码修改l

10.设置项目所有者

```
chown -R www.www /www/wwwroot/work
```

11.数据库数据传输

12.以后代码上传后更新

```
git pull 
```











```

时间修改回显
public function edit(Request $request,$id)
    {
      $data=Activity::find($id);
      $data->start_time=str_replace(" ","T",$data->start_time);
        $data->end_time=str_replace(" ","T",$data->end_time);
        if($request->isMethod("post")){
            $da= $this->validate($request,[
                "title"=>"required",
                "start_time"=>"required",
                "end_time"=>"required",
                "content"=>"required"
            ]);
//            $da=$request->post();
//           dd($da);
            $da['start_time']=str_replace("T"," ",$da['start_time']);
            $da['end_time']=str_replace("T"," ",$da['end_time']);

//            dd($da);
           $data->update($da);
            return redirect()->intended(route("admin.activity.index"))->with("success","修改成功");
        }
        
        
      //回显  
     $event['start_time']=date("Y-m-d",$event->start_time);
     $event['end_time']=date("Y-m-d",$event->end_time);
     $event['prize_time']=date("Y-m-d",$event->prize_time);

```
# Day14
##### 开发任务
微信支付

##### 实现步骤

1.接口添加

```
 // 微信支付
    wxPay: '/api/order/wxPay',
    // 订单状态
    wxStatus: '/api/order/status',
```

2.下载安准微信开发包

```
composer require "overtrue/laravel-wechat:~3.0" -vvv
```

3.生成配置

```
php artisan vendor:publish --provider="Overtrue\LaravelWechat\ServiceProvider"
```

4.修改配置文件 config/wechat.php

```
return [
    ...
    /*
     * 账号基本信息，请从微信公众平台/开放平台获取
     */
    'app_id'  => env('WECHAT_APPID', '应用ID'),         // AppID
    'secret'  => env('WECHAT_SECRET', 'your-app-secret'),   
    ……
      * 微信支付
     */
     'payment' => [
         'merchant_id'        => env('WECHAT_PAYMENT_MERCHANT_ID', '微信支付商户号'),
         'key'                => env('WECHAT_PAYMENT_KEY', '应用密钥'),
         ]
          /**
     * Guzzle 全局设置
     *
     * 更多请参考： http://docs.guzzlephp.org/en/latest/request-options.html
     */
    'guzzle' => [
        'timeout' => 3.0, // 超时时间（秒）
        'verify' => false, // 关掉 SSL 认证（强烈不建议！！！）
    ],

```

5.下载二维码生成器

```
composer require "endroid/qrcode:~2.5" -vvv
```

easywechat参考文档：<https://www.easywechat.com/docs/3.x/overview>

二维码参考文档：<https://github.com/endroid/qr-code/tree/2.x>

```
 public function wxPay( ){
        //接收id找出订单号
        $id = \request()->get("id");
        //dd($id);
        $orderModel =Order::find($id);
        //dd($orderModel);
        //1配置
        $options = config("wechat");
        $app = new Application($options);
        $payment = $app->payment;
        //2生成订单
        $attributes = [
            'trade_type'       => 'NATIVE', // JSAPI，NATIVE，APP...
            'body'             => '饿不饿点餐平台支付',
            'detail'           => '饿不饿点餐平台支付吖',
            'out_trade_no'     => $orderModel->order_code,//订单号
            'total_fee'        => $orderModel->total * 100,  // 单位：分
            'notify_url'       => 'http://xxx.com/order-notify', // 支付结果通知网址，如果不设置则会使用配置里的默认地址
            // 'openid'           => '当前用户的 openid', // trade_type=JSAPI，此参数必传，用户在商户appid下的唯一标识，
            // ...
        ];
        $order = new \EasyWeChat\Payment\Order($attributes);
        //统一下单
        $result = $payment->prepare($order);
        //dd($result);
        if ($result->return_code == 'SUCCESS' && $result->result_code == 'SUCCESS'){
            $codeUrl = $result->code_url;

            $qrCode = new QrCode($codeUrl);
            $qrCode->setSize(300);//大小
            // Set advanced options
            $qrCode
                ->setMargin(10)//外边框
                ->setEncoding('UTF-8')//编码
                ->setErrorCorrectionLevel(ErrorCorrectionLevel::HIGH)//容错级别
                ->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0])//码颜色
                ->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255])//背景色
                ->setLabel('微信扫码支付', 16, public_path("font/msyh.ttc"), LabelAlignment::CENTER)
                ->setLogoPath(public_path("images/logo.png"))//LOGO
                ->setLogoWidth(100);//LOGO大小

            // Directly output the QR code
            header('Content-Type: ' . $qrCode->getContentType());//响应类型
            exit($qrCode->writeString());
        }else{
            return $result;
        }

    }
```

6.微信异步通知

```
    //微信异步通知
    public function ok(  ){
        //1配置
        $options = config("wechat");
        $app = new Application($options);
        //2.回调
        $response = $app->payment->handleNotify(function($notify, $successful){
            // 使用通知里的 "微信支付订单号" 或者 "商户订单号" 去自己的数据库找到订单
            // $order = 查询订单($notify->out_trade_no);
            $order=Order::where("order_code",$notify->out_trade_no)->first();

            if (!$order) { // 如果订单不存在
                return 'Order not exist.'; // 告诉微信，我已经处理完了，订单没找到，别再通知我了
            }

            // 如果订单存在
            // 检查订单是否已经更新过支付状态
            if ($order->status==1) { // 假设订单字段“支付时间”不为空代表已经支付
                return true; // 已经支付成功了就不再更新了
            }

            // 用户是否支付成功
            if ($successful) {
                // 不是已经支付状态则修改为已经支付状态
                //$order->paid_at = time(); // 更新支付时间为当前时间
                $order->status = 1;
            }

            $order->save(); // 保存订单

            return true; // 返回处理完成
        });

        $response->send();
    }
```

7.更改状态

```
 public function status(){

        $id = \request()->get("id");
        $order = Order::find($id);
        return [
          'status'=>$order->status,
        ];

    }
```

#### 上线注意

页面不跳转 回调路径有问题

数据库权限  

端口放行

# Day15

### 开发任务

#### 网站优化

- 店铺列表和详情接口使用redis做缓存,减少数据库压力 
-  高并发下,使用redis解决活动报名问题（已解决）
- 自动清理超时未支付订单
- 活动列表页和活动详情页,页面静态化
- 全文索引

#### 接口安全

HTTPS+TOKEN+数字签名

#### 实现步骤

##### 超时未支付订单

1.先取出超时 未支付的订单 遍历出每一条

2.把状态修成为-1

3.修改库存  拿出这个订单的商品 读取这个商品的数量 把退回的数量加到库存当中

```
 public function clear(  ){
        /*
         * 找出需要处理的订单 要是超时的 未支付的
         * 创建时间小于当前时间多少分或者
         * 创建时间<当前时间-60*60 这个是超时
         * 状态为0
         */

       // dd($orders->toArray());
        //可以用到自动事务保证状态和库存同时修改
        DB::transaction(function(){
            $orders =Order::where("status",0)->where("created_at","<",date("Y-m-d H:i:s",time()-60*60))->get();
            //把超时未支付的订单遍历出来 并修改状态-1
            foreach($orders as $order){
                $order->status=-1;
                $order->save();

                /*
                 * 库存修改
                 * 取出当前订单的商品 在商品订单表查询
                 * 拿出要退的商品数量
                 * 修改menu表的库存
                 *
                 */
                $goods =OrderDetail::where("order_id",$order->id)->get();
                //dd($goods);
                //遍历商品 取出库存
                foreach($goods as $good){
                    $amount =$good->amount;
                    $menuId=$good->goods_id;
                    //修改库存
                    Menu::where("id",$menuId)->increment("stock",$amount);
                }
            }
        });

    }
```

4.在宝塔中添加计划任务 就能实现自动清除

##### 店铺列表用缓存redis或者文件缓存

文件缓存

1.获取缓存

2.判断缓存是否存在 存在则直接获取

3 .缓存不存在  则在数据库获取内容  并添加到缓存当中  设置自动清理缓存的时间



 ```
  /*
         * 缓存实现店铺列表
         */
        //从redis中取出来的字符串
        $shops=Cache::get("shop_index");
        if(!$shops){
            //如果redis没有 那就在数据库拿取
            //得到所有店铺，状态为1的
            $shops = Shop::where("status",1)->get();
            //把数据保存到redis 时间是分钟
            Cache::set("shop_index",$shops,10);
        }
        //dd($shops->toArray());
        //把距离和送达时间追加上去
        foreach($shops as $k=>$v){
            $shops[$k]->distance=rand(1000,5000);
            $shops[$k]->estimate_time=ceil($shops[$k]->distance/rand(100,150));
        }
      return $shops;
 ```

用原生redis做

1.获取缓存

2.判断缓存是否存在 存在则直接获取 并转换成数组 

```
$shops=json_decode($shops,true);//记得加true
```

3 .缓存不存在  则在数据库获取内容  并添加到缓存当中 要转换成字符串  设置自动清理缓存的时间

```

        $shops =Redis::get("shop_index");
        if(!$shops){
            //如果redis没有 那就在数据库拿取
            //得到所有店铺，状态为1的
            $shops = Shop::where("status",1)->get();
            Redis::setex("shop_index",60*60,json_encode($shops));
        }
        $shops=json_decode($shops,true);//记得加true
        foreach($shops as $k=>$v){
            $shops[$k]['distance']=rand(1000,5000);
            $shops[$k]['estimate_time']=ceil($shops[$k]['distance']/rand(100,150));
        }
        return $shops;
       
```

##### 全文索引

1.确认php扩展是否打开

```
pdo_sqlite
sqlite3
mbstring
```

2.安装tntsearch

```
composer require vanry/laravel-scout-tntsearch -vvv
```

3.添加全文索引config/app.php

```
'providers' => [

    // ...

    /**
     * TNTSearch 全文搜索
     */
    Laravel\Scout\ScoutServiceProvider::class,
    Vanry\Scout\TNTSearchScoutServiceProvider::class,
],
```

4.安装中文分词 require jieba-php

```
composer require fukuball/jieba-php
```

5.发布配置

```
php artisan vendor:publish --provider="Laravel\Scout\ScoutServiceProvider"
```

6.配置项中增加 tntsearch ；在/config/scout.php ；

```
'tntsearch' => [
    'storage' => storage_path('indexes'), //必须有可写权限
    'fuzziness' => env('TNTSEARCH_FUZZINESS', false),
    'searchBoolean' => env('TNTSEARCH_BOOLEAN', false),
    'asYouType' => false,

    'fuzzy' => [
        'prefix_length' => 2,
        'max_expansions' => 50,
        'distance' => 2,
    ],

    'tokenizer' => [
        'driver' => env('TNTSEARCH_TOKENIZER', 'default'),

        'jieba' => [
            'dict' => 'small',
            //'user_dict' => resource_path('dicts/mydict.txt'), //自定义词典路径
        ],

        'analysis' => [
            'result_type' => 2,
            'unit_word' => true,
            'differ_max' => true,
        ],

        'scws' => [
            'charset' => 'utf-8',
            'dict' => '/usr/local/scws/etc/dict.utf8.xdb',
            'rule' => '/usr/local/scws/etc/rules.utf8.ini',
            'multi' => 1,
            'ignore' => true,
            'duality' => false,
        ],
    ],

    'stopwords' => [
        '的',
        '了',
        '而是',
    ],
],
```

7.增加配置项 /.env

```
SCOUT_DRIVER=tntsearch
TNTSEARCH_TOKENIZER=jieba
```

8.模型中定义全文搜索；/app/Models/Shop.php

```
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Shop extends Model
{
    use Searchable;//一定要有命名空间

    /**
     * 索引的字段
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return $this->only('id', 'shop_name');
    }
}
```

9.生成索引

```
php artisan scout:import "App\Models\Shop"
```

10.应用

```
 public function index(  ){

        $keyword = \request("keyword");
        //dd($keyword);
        if ($keyword != null) {
            //$shops = Shop::where("status",1)->where("shop_name","like","%{$keyword}%")->get();
            $shops = Shop::search($keyword)->get();
            //dd($shops->toArray());
        }else{//一定要else  不然搜索不出来
            $shops=Cache::get("shop_index");
            if(!$shops){
                //如果文件中没有 那就在数据库拿取
                //得到所有店铺，状态为1的
                $shops = Shop::where("status",1)->get();
                //把数据保存到redis 时间是分钟
                Cache::set("shop_index",$shops,1);
            }

        }
        /*
         * 缓存实现店铺列表
         */
        //从文件中取出来的缓存的

        //dd($shops->toArray());
        //把距离和送达时间追加上去
        foreach($shops as $k=>$v){
            $shops[$k]->distance=rand(1000,5000);
            $shops[$k]->estimate_time=ceil($shops[$k]->distance/rand(100,150));
        }
      return $shops;
       

   }
```

##### 接口安全

###### 1.https

###### 2.token

1.用户提交“用户名”和“密码”，实现登录

2.登录成功后 ，服务端返回一个 token，生成规则参考如下：token = md5('用户的id' + 'Unix时间戳')

3.服务端将生成 token和用户id的对应关系保存到redis，并设置有效期（例如7天）

4.客户端每次接口请求时，如果接口需要用户登录才能访问，则需要把 user_id 与 token 传回给服务端

5.服务端验证token 和用户id的关系，更新token 的过期时间（延期，保证其有效期内连续操作不掉线）

###### 3.数字签名

1.对除签名外的所有请求参数按key做升序排列 （假设当前时间的时间戳是12345678）

例如：有c=3,b=2,a=1 三个参，另加上时间戳后， 按key排序后为：a=1，b=2，c=3，timestamp=12345678。

2.把参数名和参数值连接成字符串，得到拼装字符：a1b2c3timestamp12345678

3.用密钥连接到接拼装字符串头部和尾部，然后进行32位MD5加密，最后将到得MD5加密摘要转化成大写。





