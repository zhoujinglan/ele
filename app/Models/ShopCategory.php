<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ShopCategory
 *
 * @property int $id
 * @property string $name 分类名称
 * @property string $img 图片
 * @property int $status 状态
 * @property int $sort 排名
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ShopCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ShopCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ShopCategory whereImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ShopCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ShopCategory whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ShopCategory whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ShopCategory whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ShopCategory extends Model
{
    //
    protected $fillable=['name','img','sort','status'];


}
