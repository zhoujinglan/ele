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
