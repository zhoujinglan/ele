<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Activity
 *
 * @property int $id
 * @property string|null $title 活动名称
 * @property string|null $content 活动详情
 * @property int|null $start_time 活动开始时间
 * @property int|null $end_time 活动结束时间
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Activity whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Activity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Activity whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Activity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Activity whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Activity whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Activity whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Activity extends Model
{
    //
    protected $fillable=['title','content','start_time','end_time'];
}
