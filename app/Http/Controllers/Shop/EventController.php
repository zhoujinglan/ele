<?php

namespace App\Http\Controllers\Shop;

use App\Models\Event;
use App\Models\EventPrize;
use App\Models\EventUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

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
        $num=EventUser::where('event_id',$event->id)->count();
        //dd($num);
        $user=EventUser::where('event_id',$event->id)->first();
        //dd($user);
        if($num > $event->num ){
                return back()->with("success","报名已满");
        }

        $data['user_id']=Auth::user()->id;
        //dd( $data['user_id']);
        $data['event_id']=$id;
        if( $data['user_id'] == $user->user_id){
            return back()->with("warning","你已报名");
        }
        EventUser::create($data);
        return back()->with("success","报名成功 等待开奖");

    }
    //查看
    public function look($id){
        $prizes=EventPrize::where("event_id",$id)->get();
        return view("shop.event.look",compact("prizes"));
    }
}
