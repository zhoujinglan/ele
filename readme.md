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
1.完善第一天的 判断是有有店铺
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
            //验证
           $data= $this->validate( $request, [
                'name'     => 'required',
                "password" => "required",

            ] );
           //dd($data);

            if( Auth::attempt( $data, $request->has( "remember"))){
                //判断商铺的状态
               $id=Auth::user()->id;
               //dd($id);
              //$shop[0]=  DB::select("select status from shops where user_id=:id",[$id]);
                //dd($shop[0][0]);

               $shop= Shop::where("user_id",$id)->first();
               //dd($shop->status);
                if(($shop->status) == 1){
                    //登陆成功
                    //return 1;
                    return redirect()->intended(route("index"))->with("success","登录成功");
                }elseif(($shop->status) == -1){
                    //return -1;
                    return back()->with("danger","你的店铺已禁用  请重新注册账号");
                   // return redirect()->intended(route("shop.user.add"))->with();
                }else{
                    //return 0;
                    return redirect()->intended(route("shop.user.login"))->with("warning","店铺在审核 请耐心等待");
                }


            }else{
                //登录失败
                return redirect()->back()->withInput()->with("danger","账号或密码错误");
            }
        }
            //显示视图
        return view("shop.user.login");
    }
```


