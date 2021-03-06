<?php

namespace App\Http\Controllers\Shop;

use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    //显示店铺订单
    public function index(  ){

        $code =request()->get("order_code");
        $status=request()->get("status");
        $query =Order::orderBy("id");
        if($code != null){
            $query->where("order_code",$code);
        }
        if($status != null){
            $query->where("status",$status);
        }
        $orders=$query->where("shop_id",Auth::user()->shop->id)->paginate(3);

        //引入视图
        return view("shop.order.index",compact("orders"));
    }
   //获取每日销售量
    public function day(  ){

        //先得到当前店铺的id
        $shop_id= Auth::user()->shop->id;


        //dd($shop_id);
        //DATE_FORMAT(created_at,'%Y-%m-%d') 按时间分组
        $datas = Order::where("shop_id",$shop_id)
               ->select(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d') as date,COUNT(*) as nums,SUM(total) as money"))
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
        return view("shop.order.day",compact("datas","url"));

    }
    //每月
    public function month(  ){
        //先得到当前店铺的id
        $shop_id= Auth::user()->shop->id;
        //dd($shop_id);
        //DATE_FORMAT(created_at,'%Y-%m-%d') 按时间分组
        $datas = Order::where("shop_id",$shop_id)
                      ->select(DB::raw("DATE_FORMAT(created_at,'%Y-%m') as date,COUNT(*) as nums,SUM(total) as money"))
                      ->groupBy('date')
                      ->get();
        //dd($datas);
        //引入视图
        return view("shop.order.month",compact("datas"));
    }

    //菜品销量统计[按日统计,按月统计,累计]（每日、每月、总计）
    //总量
    public function menu(){
        //
        //dd(date('Y-m-d H:m:s',strtotime('+2 day')));
        //1.找到当前店铺所有订单 ID
        $ids=  Order::where("shop_id",Auth::user()->shop->id)->pluck("id");
        $data= OrderDetail::select(DB::raw('SUM(amount) as nums,sum(goods_price) as total'))->whereIn("order_id",$ids)->get();
        //dd($data[0]->nums);
        return view("shop.order.menu",compact('data'));

    }
  //按菜品日日销量
    public function menuDay(Request $request){
        //默认显示当前的菜品销量
        $date=$request->day??date("Y-m-d",time());
        //dd($date);
        //$shopId=$request->shop_id;
        //登录用户
        //先找到店铺ID
        $shopId=Auth::user()->shop->id;
        //再通过店铺ID把属于这个店的所有订单id 都找出来
        $orderIds=Order::where("shop_id",$shopId)->whereDate('created_at', $date)->pluck("id")->toArray();
        //商品
        $datas=   OrderDetail::select(DB::raw("goods_id,goods_name,sum(amount) as nums,sum(amount * goods_price) as price"))->whereIn("order_id",$orderIds)->groupBy("goods_id")->get();
        //dd($datas->toArray());


        //$datas=OrderDetail::orderBy("created_at", 'desc');
//        dd($datas->toArray());
        //把事件只读出月份 搜索用
        $months = OrderDetail::select(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d') as date,COUNT(*) as nums,SUM(goods_price) as money,goods_name" )) ->groupBy('date' )->get();

        return view("shop.order.menu_day",compact('datas',"months","url"));

    }
//每月统计
    public function menuMonth(){

        $datas = OrderDetail::select(DB::raw("DATE_FORMAT(created_at,'%Y-%m') as date,COUNT(*) as nums,SUM(goods_price) as money,goods_name"))
                            ->groupBy('date','goods_name')
                            ->get();
        //dd($datas);
        return view("shop.order.menu_month",compact('datas'));

    }


    //修改状态
    public function status($id,$status ){
     //dd($status);
        //根据传递的值 修改状态
        $order =Order::find($id);
        $order->status=$status;
        $order->save();
        return back()->with("success","修改成功");
        
    }
    //查看某一条
    public function look($id  ){
            $order =Order::find($id);
            //视图
        return view("shop.order.look",compact("order"));
    }
}
