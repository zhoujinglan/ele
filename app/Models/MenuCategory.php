<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MenuCategory
 *
 * @property int $id
 * @property string $name 分类名称
 * @property string $type_accumulation 菜品编号（a-z前端使用）
 * @property int $shop_id 所属商家ID
 * @property string $description 描述
 * @property int $is_selected 是否默认分类
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Menu[] $goodsList
 * @property-read \App\Models\Shop $shop
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MenuCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MenuCategory whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MenuCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MenuCategory whereIsSelected($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MenuCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MenuCategory whereShopId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MenuCategory whereTypeAccumulation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MenuCategory whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class MenuCategory extends Model
{
    //
    protected $fillable=['name','type_accumulation','shop_id','description','is_selected'];

    public function shop( ){
        return $this->belongsTo(Shop::class,"shop_id");
    }

    //通过分类找菜品goods_list=====>goodsList
    public function goodsList(){
        return $this->hasMany(Menu::class,"category_id");
    }




}
