<?php

namespace App\Http\Controllers\Shop;

use App\Models\Activity;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ActivityController extends Controller
{
    //
    public function index( ){

        $activities=Activity::where("end_time",">=", time())->get();
        // dd($activities[0]->start_time);
        //dd(date('Y-m-d H:i:s', time()));//转成当前时间
        //引入视图
        return view("shop.activity.index",compact("activities"));

    }
}
