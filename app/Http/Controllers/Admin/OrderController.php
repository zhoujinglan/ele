<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class OrderController extends BaseController
{
    //显示店铺订单
    public function index(  ){

        //$orders=Order::paginate(3)->groupBy('shop_id');
        $orders=Order::select(DB::raw("COUNT(*) as nums,SUM(total) as money,shop_id"))
            ->groupBy('shop_id')
        ->paginate(3);
        //dd($orders);

        //引入视图
        return view("admin.order.index",compact("orders"));
    }
    //获取每日销售量
    public function day(  ){

        //dd($shop_id);
        //DATE_FORMAT(created_at,'%Y-%m-%d') 按时间分组
        $datas = Order::select(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d') as date,COUNT(*) as nums,SUM(total) as money"))
                      ->groupBy('date');
        //接收参数  搜索
        $start = request()->get('start');
        $end =request()->get("end");
        $url =request()->query();


        if ($start !== null) {
            $datas->whereDate("created_at", ">=", $start);
        }
        if ($end !== null) {
            $datas->whereDate("created_at", "<=", $end);
        }
        $datas =   $datas->get();
        //dd($datas);
        //引入视图
        return view("admin.order.day",compact("datas","url"));

    }

    //每月
    public function month(  ){
        //先得到当前店铺的id
        //$shop_id= Auth::user()->shop->id;
        //dd($shop_id);
        //DATE_FORMAT(created_at,'%Y-%m-%d') 按时间分组
        $datas = Order::select(DB::raw("DATE_FORMAT(created_at,'%Y-%m') as date,COUNT(*) as nums,SUM(total) as money"))
                      ->groupBy('date')
                      ->get();
        //dd($datas);
        //引入视图
        return view("admin.order.month",compact("datas"));
    }
}
