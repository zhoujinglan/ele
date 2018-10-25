<?php

namespace App\Http\Controllers\Shop;

use App\Models\Shop;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IndexController extends BaseController
{
    //显示后台
    public function index(  ){
       // $id=Auth::user()->id;

        //if($id=)
        //判断是否拥有店铺
       //$id= DB::where("user_id",$id)->first();
       //dd($id);
        if(Auth::user()->shop ===null){
            //跳转到添加店铺
            return redirect()->route("shop.apply")->with("warning","你还没有创建店铺");
        }

        return view("shop.index.index");
    }

}
