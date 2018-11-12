<?php

namespace App\Http\Controllers\Api;

use App\Models\Address;
use App\Models\Cart;
use App\Models\Member;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Shop;
use EasyWeChat\Foundation\Application;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\LabelAlignment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Mrgoon\AliSms\AliSms;
use Endroid\QrCode\QrCode;


class OrderController extends BaseController
{
    //添加订单
    public function add(Request $request){
            //得到前端address_id传来的数据  查出地址
        $id = $request->post("address_id");
        $address=Address::find($id);
        //判断地址是否有误
        if($address === null){
            //地址有误 返回错误信息
            return [
                "status"=>"false",
                'message'=>'请选择正确的地址',
            ];
        }
        //地址正确的情况
        //添加数据 进行赋值
        //user_id
        $data['user_id']= $request->post('user_id');
        //shop_id  先通过user_id查询购物车的一条数据  获得第一条数据的id 通过其中的商品id找到shop_id  所有的商品在同一家店购买 所有只要查取第一条数据就能查询到相关的店铺信息
        $carts = Cart::where("user_id",$data['user_id'])->get();
        //dd($carts[0]->goods_id);
        $shop_id = Menu::find($carts[0]->goods_id)->shop_id;
        //dd($shop_id);
        $data['shop_id']=$shop_id;
        //订单号的生成
        $data['order_code']=date("ymdHis").rand(1000,9999);//y小写则是去掉20 订单号181102141321+三位随机数
        //地址拼接
        $data['provence']=$address->provence;
        $data['city']=$address->city;
        $data["area"]=$address->area;
        $data['address']=$address->detail_address;
        $data['tel']=$address->tel;
        $data["name"]=$address->name;
        //算出总价 =单价乘以数量
        $total = 0;
        foreach($carts as $k =>$v){
            //得到菜品
            $good=Menu::where('id',$v->goods_id)->first();
            //通过shop_id获取配送费  得到店家的配送费
            //配送费
            //$shop= Shop::where('id',$v->goods_id)->first();
            //dd($shop);
            $total+=$v->amount*$good->goods_price;
        }
        $data['total']=$total;
        //状态  待支付
        $data['status']=0;
        //订单入库 与订单商品两张表同时操作 要考虑到事务 事务分手动和自动事务  这种情况采用手动事务   手动事务就要先开启事务  提交  回滚  解决的话加要捕获异常 抛出异常

        DB::beginTransaction();//开启事务

        try{

            //订单入库
            $order = Order::create($data);

            //订单商品的操作
            //1.遍历购物车 得到当前的购物内容
            foreach($carts as $kk =>$cart){
                //得当当前购物车的内容
                $menu = Menu::find($cart->goods_id);
                //判断是否有库存
                if($cart->amount > $menu->stock ){
                    //库存不足 抛出异常
                    throw new \Exception($menu->goods_img."库存不足");
                }

                //库存充足 减去
                $menu->stock=$menu->stock -$cart->amount;
                //保存
                $menu->save();
                //数据添加
                $time=date('Y-m-d H:m:s');
                $updated=date('Y-m-d H:m:s',strtotime('+2 day'));
                OrderDetail::insert([
                    'order_id'=>$order->id,
                    'goods_id'=>$cart->goods_id,
                    'amount'=>$cart->amount,
                    'goods_name'=> $menu->goods_name,
                    'goods_img'=>$menu->goods_img,
                    'goods_price'=>$menu->goods_price,
                    'created_at'=>$time,
                    'updated_at'=>$updated,

                                    ]);
            }
            //订单列表添加成功 把购物车的内容清空
            Cart::where("user_id",$request->post("user_id"))->delete();
            //事务提交
            DB::commit();


        }catch(\Exception $exception){

            //回滚
            DB::rollBack();
            return[
                'status'=>"false",
                'message'=>$exception->getMessage(),
            ];
        }

//

        return [
            'status'=>"true",
            'message'=>"添加成功",
            'order_id'=>$order->id,
        ];
        

    }
    
    //订单显示
    public function index( Request $request ){

        $orders = Order::where("user_id", $request->input('user_id'))->get();
        //dd($orders);
        $datas = [];
        foreach($orders as $order){
            //dd($order);
            $data['id']=$order->id;
            $data['order_code']=$order->order_code;
            $data['order_status']=$order->order_status;//用到获取器
            $data['shop_name']=$order->shop->shop_name;
            $data['shop_img']=$order->shop->shop_img;
            $data['order_price']=$order->total;
            $data['order_address']=$order->provence . $order->city . $order->area . $order->address;
            $data['goods_list']=$order->order_details;//order_details是order模型的方法  获得订单商品
            $datas[]=$data;

        }
        return $datas;
    }

    //订单显示指定
    public function detail(Request $request){
        $order =Order::find($request->input("id"));
        //dd($order);
        $data['id']=$order->id;
        $data['order_code']=$order->order_code;
        $data['order_birth_time']=(string)$order->created_at;
        $data['order_status']=$order->order_status;//用到获取器
        $data['shop_name']=$order->shop->shop_name;
        $data['shop_img']=$order->shop->shop_img;
        $data['order_price']=$order->total;
        $data['order_address']=$order->provence . $order->city . $order->area . $order->address;
        $data['goods_list']=$order->order_details;
       // dd($data);
        return $data;
    }

    public function pay(Request $request){
        //得到订单id
        $id =$request->post("id");
       // dd($id);
        //得到订单
        $order=Order::find($id);

        //得到用户
        $member=Member::find($order->user_id);
        //判断用户余额是否足够
        if($order->total > $member->money){
            return [
                'status'=>"false",
                'message'=>"余额不足 请换个支付",
            ];
        }

        //余额充足 请支付  减去订单金额
        DB::transaction(function() use($member,$order){
            $member->money=$member->money -$order->total;
            $member->save();
            //更改订单状态
            $order->status=1;
            $order->save();
        });
        //支付成功
        //得到电话号码
        $tel =$order->tel;
        //         dd($tel);
        //        $tel=18602302240;
        //得到店铺id
        $shop_id = $order->shop_id;
        //得到店铺信息
        $shop=Shop::where("id",$shop_id)->first();
        //dd($shop->shop_name);
        //发短信
        $code="最让你舍不得的平台eles的".$shop->shop_name;
        //        dd($code);
        //4. 把验证码发给手机 用到阿里云短信服务
        $config = [
            'access_key' => env("ALIYUNU_ACCESS_ID"),
            'access_secret' => env("ALIYUNU_ACCESS_KEY"),
            'sign_name' => '个人生活记录',
        ];

        $sms=New AliSms();
        //        dd($tel);
        $response = $sms->sendSms($tel, "SMS_150575336", ['name'=> $code], $config);
        //        dd($response);

        //返回成功信息
        return [
            'status'=>"true",
            'message'=>"支付成功",
        ];


    }

    public function wxPay( ){
        //接收id找出订单号
        $id = \request()->get("id");
        //dd($id);
        $orderModel =Order::find($id);
        //dd($orderModel);
        //1配置
        $options = config("wechat");
        $app = new Application($options);
        $payment = $app->payment;
        //2生成订单
        $attributes = [
            'trade_type'       => 'NATIVE', // JSAPI，NATIVE，APP...
            'body'             => '饿不饿点餐平台支付',
            'detail'           => '饿不饿点餐平台支付吖',
            'out_trade_no'     => $orderModel->order_code,//订单号
            'total_fee'        => $orderModel->total * 100,  // 单位：分
            'notify_url'       => 'http://xxx.com/order-notify', // 支付结果通知网址，如果不设置则会使用配置里的默认地址
            // 'openid'           => '当前用户的 openid', // trade_type=JSAPI，此参数必传，用户在商户appid下的唯一标识，
            // ...
        ];
        $order = new \EasyWeChat\Payment\Order($attributes);
        //统一下单
        $result = $payment->prepare($order);
        //dd($result);
        if ($result->return_code == 'SUCCESS' && $result->result_code == 'SUCCESS'){
            $codeUrl = $result->code_url;

            $qrCode = new QrCode($codeUrl);
            $qrCode->setSize(300);//大小
            // Set advanced options
            $qrCode
                ->setMargin(10)//外边框
                ->setEncoding('UTF-8')//编码
                ->setErrorCorrectionLevel(ErrorCorrectionLevel::HIGH)//容错级别
                ->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0])//码颜色
                ->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255])//背景色
                ->setLabel('微信扫码支付', 16, public_path("font/msyh.ttc"), LabelAlignment::CENTER)
                ->setLogoPath(public_path("images/logo.png"))//LOGO
                ->setLogoWidth(100);//LOGO大小

            // Directly output the QR code
            header('Content-Type: ' . $qrCode->getContentType());//响应类型
            exit($qrCode->writeString());
        }else{
            return $result;
        }

    }
    //微信异步通知
    public function ok(  ){
        //1配置
        $options = config("wechat");
        $app = new Application($options);
        //2.回调
        $response = $app->payment->handleNotify(function($notify, $successful){
            // 使用通知里的 "微信支付订单号" 或者 "商户订单号" 去自己的数据库找到订单
            // $order = 查询订单($notify->out_trade_no);
            $order=Order::where("order_code",$notify->out_trade_no)->first();

            if (!$order) { // 如果订单不存在
                return 'Order not exist.'; // 告诉微信，我已经处理完了，订单没找到，别再通知我了
            }

            // 如果订单存在
            // 检查订单是否已经更新过支付状态
            if ($order->status==1) { // 假设订单字段“支付时间”不为空代表已经支付
                return true; // 已经支付成功了就不再更新了
            }

            // 用户是否支付成功
            if ($successful) {
                // 不是已经支付状态则修改为已经支付状态
                //$order->paid_at = time(); // 更新支付时间为当前时间
                $order->status = 1;
            }

            $order->save(); // 保存订单

            return true; // 返回处理完成
        });

        $response->send();
    }
    //修改状态
    public function status(){

        $id = \request()->get("id");
        $order = Order::find($id);
        return $order;

    }

    /*
     * 处理超时未支付订单
     * 
     */
    public function clear(  ){
        /*
         * 找出需要处理的订单 要是超时的 未支付的
         * 创建时间小于当前时间多少分或者
         * 创建时间<当前时间-60*60
         */

       // dd($orders->toArray());
        //可以用到自动事务保证状态和库存同时修改
        DB::transaction(function(){
            $orders =Order::where("status",0)->where("created_at","<",date("Y-m-d H:i:s",time()-60*60))->get();
            //把超时未支付的订单遍历出来 并修改状态-1
            foreach($orders as $order){
                $order->status=-1;
                $order->save();

                /*
                 * 库存修改
                 * 取出当前订单的商品 在商品订单表查询
                 * 拿出要退的商品数量
                 * 修改menu表的库存
                 *
                 */
                $goods =OrderDetail::where("order_id",$order->id)->get();
                //dd($goods);
                //遍历商品 取出库存
                foreach($goods as $good){
                    $amount =$good->amount;
                    $menuId=$good->goods_id;
                    //修改库存
                    Menu::where("id",$menuId)->increment("stock",$amount);
                }
            }
        });

    }
}
