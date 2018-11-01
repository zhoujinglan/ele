<?php

namespace App\Http\Controllers\Api;

use App\Models\MenuCategory;
use App\Models\Shop;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShopController extends Controller
{
    //获得所有店铺
    public function index(  ){

        $keyword = \request("keyword");
        if ($keyword!=null) {
            $shops = Shop::where("status",1)->where("shop_name","like","%{$keyword}%")->get();
        }else{
            //得到所有店铺，状态为1的
            $shops = Shop::where("status",1)->get();
        }


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
