<?php

namespace App\Http\Controllers\Shop;


use App\Models\Shop;
use App\Models\ShopCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ShopController extends BaseController
{
    //店铺申请
    public function apply(Request $request){

        //显示视图

//        //判断是否注册店铺
//        if(Auth::user()->id){
//            //跳转到添加店铺
//            return back()->with("warning","你已经申请了店铺  ");
//        }

        if($request->isMethod("post")){
            /*
            $data= $this->validate( $request, [
                'shop_name'     => 'required',
                "s_c_id" => "required",
                "shop_img" => "required|img",
                "shop_rating" => "required",
                "brand" => "required",

            ] );
            */
            $data = $request->post();
            //dd($data);
            $file=$request->file('img');
            $data['shop_img']=$file->store('good_img','image');
            //dd($user_id=Auth::user()->id);

            $data['user_id']=Auth::user()->id;
            $data['status']=0;
            //dd($user_id);
            Shop::create($data);
            //页面跳转
            Auth::logout();
            return redirect()->intended(route("user.login"))->with("success","申请成功 请等待审核");

        }

        //显示所有分类  得到显示的

        $categories=ShopCategory::where("status",1)->get();
        return view("shop.shop.apply",compact("categories"));
    }

    //添加图片的方法
    public function upload(Request $request){

        //上传处理
        $file=$request->file("file");//内部的文件  没有在html中显示
        if($file){
            //有文件进行上传
            $url = $file->store("menu");
            //得到真实地址  把http加载进去
            // $url = Storage::url($url);
            $data['url']=$url;
            return $data;
        }

    }

}
