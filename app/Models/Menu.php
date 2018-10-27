<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

}
