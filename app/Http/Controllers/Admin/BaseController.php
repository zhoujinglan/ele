<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Mockery\Matcher\Closure;

class BaseController extends Controller
{
    //
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



       $this->middleware("auth:admin",[
           "except"=>["login","reg","logout"]

       ]);



    }
}
