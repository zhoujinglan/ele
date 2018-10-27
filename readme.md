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
