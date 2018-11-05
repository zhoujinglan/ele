<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Menu
 *
 * @property int $id
 * @property string|null $goods_name 分类名称
 * @property int|null $rating 评分
 * @property int|null $shop_id 所属商家id
 * @property int|null $category_id 所属分类id
 * @property float|null $goods_price 价格
 * @property string|null $description 描述
 * @property int|null $month_sales 月销量
 * @property int|null $rating_count 评分数量
 * @property string|null $tips 提示信息
 * @property int|null $satisfy_count 满意度数量
 * @property int|null $satisfy_rate 满意度评分
 * @property string|null $goods_img 商品图片
 * @property int|null $status 状态
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\MenuCategory|null $menu_category
 * @property-read \App\Models\Shop|null $menu_shop
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Menu whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Menu whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Menu whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Menu whereGoodsImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Menu whereGoodsName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Menu whereGoodsPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Menu whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Menu whereMonthSales($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Menu whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Menu whereRatingCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Menu whereSatisfyCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Menu whereSatisfyRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Menu whereShopId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Menu whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Menu whereTips($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Menu whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Menu extends Model
{
    //
    protected $fillable=['goods_name','shop_id','category_id','goods_price','description','tips','goods_img','status'];

    public function menu_category( ){
        return $this->belongsTo(MenuCategory::class,"category_id");

    }
    public function menu_shop(){
        return $this->belongsTo(Shop::class,"shop_id");
    }

    public function getGoodsImgAttribute($value){
        return env("ALIYUN_OSS_URL").$value;
    }


}
