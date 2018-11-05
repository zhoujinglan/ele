<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\OrderDetail
 *
 * @property int $id
 * @property int $order_id 订单id
 * @property int $goods_id 商品id
 * @property int $amount 商品数量
 * @property string $goods_name 商品名称
 * @property string $goods_img 商品图片
 * @property float $goods_price 商品价格
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderDetail whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderDetail whereGoodsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderDetail whereGoodsImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderDetail whereGoodsName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderDetail whereGoodsPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderDetail whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderDetail whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OrderDetail extends Model
{
    //
}
