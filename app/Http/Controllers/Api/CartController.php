<?php

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use App\Models\Menu;
use App\Models\Shop;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CartController extends BaseController
{
    //购物车显示
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

            //配送费
          //  $shop= Shop::where('id',$good->goods_id)->first();
           // dd($shop);
            //数量
            //$good->goods_img=env("ALIYUN_OSS_URL").$good->goods_img;
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
    //添加订单
    public function add(Request $request  ){

        $user_id = $request->post( "user_id" );
        $goodLists   = $request->post( "goodsList" );
        $goodsCounts = $request->post( "goodsCount" );
        //清空当前用户user_id的购物车 避免把之前的已买的商品再次购买
        Cart::where("user_id",$user_id)->delete();
        //dd($goodLists);
        foreach( $goodLists as $k => $v ){
            //dd($k );
            $data = [
              'user_id'=>$user_id,
              'goods_id'=>$v,//添加了哪些商品

              'amount'=>$goodsCounts[$k],//一件商品的数量
            ];
            Cart::create($data);
        }
        return [
            'status' => "true",
            "message" => "添加成功"
        ];
    }
}
