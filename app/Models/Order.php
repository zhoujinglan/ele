<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Order
 *
 * @property int $id
 * @property int $user_id 用户id
 * @property int $shop_id 商家id
 * @property string $order_code 订单号
 * @property string $name 收货人姓名
 * @property string $tel 收货人电话
 * @property string $provence 省份
 * @property string $city 市
 * @property string $area 区
 * @property string $address 详细地址
 * @property float $total 价格
 * @property int $status 状态(-1:已取消,0:待支付,1:待发货,2:待确认,3:完成)
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereArea($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereOrderCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereProvence($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereShopId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereTel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereUserId($value)
 * @mixin \Eloquent
 */
class Order extends Model
{
        //声明一个静态属性
    static public $statusText=[-1 => "已取消", 0 => "代付款", 1 => "待发货", 2 => "待确认", 3 => "完成"];
    //
    protected $fillable=["user_id","shop_id","order_code","provence","city","area","address","tel","name","total","status"];

    //获取器 获得getOrderStatus 这是得到数据库中不存在的字段

    public function getOrderStatusAttribute()
    {
        //不存在的去找数据库存在的字段
        return self::$statusText[$this->status];//-1 0 1 2 3
    }
   //通过shop_id查询shop表的内容
    public function shop()
    {
        return $this->belongsTo(Shop::class, "shop_id");
    }
    //
    public function order_details(  ){
        return $this->hasMany(OrderDetail::class,"order_id");

    }

}
