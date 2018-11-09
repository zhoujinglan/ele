<?php

namespace App\Http\Controllers\Admin;

use App\Models\Event;
use App\Models\EventPrize;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EventPrizeController extends Controller
{
    /*
     * 添加抽奖活动奖品表
     */
    public function index(  ){
        $prizes=EventPrize::all();
        return view("admin.event_prize.index",compact("prizes"));
    }
    public function add(Request $request  ){
        if($request->isMethod("post")){
            $data =$request->post();
            //dd($data);
            EventPrize::create($data);
            return redirect()->route("admin.event_prize.index")->with("success","添加成功");
        }
        //得到活动
        $events=Event::where("prize_time",">",time())->get();
        return view("admin.event_prize.add",compact("events"));
    }
}
