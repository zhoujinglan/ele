<?php

namespace App\Http\Controllers\Admin;

use App\Models\Shop;
use App\Models\ShopCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShopController extends Controller
{
    //显示
    public function add(Request $request,$id ){
        //获得所有
        //判断接收防方式
        if($request->isMethod("post")){

         $this->validate( $request, [
             'shop_name'   => 'required',
             "shop_category_id" => "required",
             "img" => "required",


         ] );

            $data = $request->post();
            //dd($data);
            $file = $request->file( 'img' );
            $data['shop_img'] = $file->store( 'shop_img', 'image' );
            //dd($user_id=Auth::user()->id);

            $data['user_id'] = $id;
            $data['status']  = 1;
            //dd($user_id);
            //dd($data);

            Shop::create( $data );
            //页面跳转

            return redirect()->intended( route( "shop.list" ))->with( "success", "添加成功" );

        }

        //显示视图
        //获得所有店铺分类
        $categories=ShopCategory::where("status",1)->get();
        return view("admin.shop.add",compact("categories"));

    }
}
