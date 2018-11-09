<?php

namespace App\Http\Controllers\Shop;

use App\Models\Event;
use App\Models\EventPrize;
use App\Models\EventUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class EventController extends Controller
{
    //
    public function index(  ){


        $events =Event::all();
        return view("shop.event.index",compact("events"));
    }
        //活动报名
    public function join($id  ){
        $event=Event::find($id);
       // dd($event);
       // $num=EventUser::where('event_id',$event->id)->count();
        $num=Redis::get("event_num:".$id);//取出redis中限制报名的人数
        //dd($num);

        //dd($num);
        //$user=EventUser::where('event_id',$event->id)->first();
        //dd($user);
        $users=Redis::scard("event:".$id);//取出已报名人数
        //dd($users);
        if($users < $num ){
            //若报名人数没有满  把报名的人加入缓存
            $userId=Auth::user()->id;
            Redis::sadd("event:".$id,$userId);
            return back()->with("success","报名成功 等待开奖");
        }

        return back()->with("success","报名已满");



    }
    //查看
    public function look($id){
        $prizes=EventPrize::where("event_id",$id)->get();
        return view("shop.event.look",compact("prizes"));
    }
}
