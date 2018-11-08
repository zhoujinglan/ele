<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\Types\Self_;

class Nav extends Model
{
    //
    protected $fillable=['name','url','sort','pid'];

    public static function navs1(  ){
        $admin=Auth::guard("admin")->user();
        $navs =self::where("pid",0)->get();
        //判断是否是一号管理员
        if($admin->id==1){
            return $navs;
        }
        foreach($navs as $k1=>$v1){
            //找出顶级父类下的子类 找出一个即可 只要没有子类的就把顶级父类删掉
            $child =self::where("pid",$v1->id)->first();
            //如果没有就删除
            if($child == null){
                unset($navs[$k1]);
            }
            //判断当前儿子有没有权限 没有权限的不显示出来
            $childs =self::where("pid",$v1->id)->get();
            //声明一个变量
            $num=0;
            foreach($childs as $k2=>$v2){
                //判断权限
                if($admin && !$admin->can($v2->url)){
                    $num++;
                }
            }
            //判断num和儿子个数一样 干掉父亲
            if ($num==count($childs)){
                unset($navs[$k1]);
            }

        }
        return $navs;

    }

}
