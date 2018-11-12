<?php

namespace App\Http\Controllers\Admin;

use App\Models\Event;
use App\Models\EventPrize;
use App\Models\EventUser;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;

class EventController extends BaseController
{
    /*
     * 抽奖活动管理
     */
    public function index(  ){


        $events =Event::all();
       // dd($events);
        //$events[0]['start_time']=date("Y-m-d H:i:s", $events[0]['start_time']);
        //dd( $events[0]['start_time']);
        return view("admin.event.index",compact("events"));
    }
    //添加活动’
    public function add(Request $request ){
        //
        if($request->isMethod("post")){
            $this->validate($request, [
                'title' => 'required',
                'content' => 'required',
            ]);
            $data =$request->post();
            $data['start_time'] =strtotime($data['start_time']);
            $data['end_time'] =strtotime($data['end_time']);
            $data['prize_time'] =strtotime($data['prize_time']);
            $data['is_prize']=0;

            //dd($data);
            //数据入库
            $event=Event::create($data);
            //把报名人数添加到缓存中 //记得打开redis 服务
            Redis::set("event_num:".$event->id,$event->num);
            return redirect()->route("admin.event.index")->with("success","添加成功");
        }

        //视图
        return view("admin.event.add");
    }
        /*
         * 活动修改
         */
    public function edit( Request $request,$id ){
        $event=Event::find($id);
        $event['start_time']=date("Y-m-d",$event->start_time);
        $event['end_time']=date("Y-m-d",$event->end_time);
       $prize_time=date("Y-m-d H:i",$event->prize_time);
        $event['prize_time']=str_replace(" ","T",$prize_time);
       //   dd( $event['prize_time']);
        if($request->isMethod("post")){
            //验证

            $data =$request->post();
            $data['start_time'] =strtotime($data['start_time']);
            $data['end_time'] =strtotime($data['end_time']);
            $data['prize_time'] =strtotime($data['prize_time']);
            //dd($data);
            //$num=Redis::get("event_num:".$id);
            //dd($num);
            $event= $event->update($data);
                //dd($event);
            //把修改的报名人数添加到缓存中 //记得打开redis 服务
            Redis::set("event_num:".$id,$data['num']);
            return redirect()->route("admin.event.index")->with("success","修改成功");

           }
           //引入视图
        return view("admin.event.edit",compact('event'));
        }
    //开奖活动
    public function open(Request $request,$id){
        //开奖前把报名人数同步到数据库中
        $users=Redis::smembers("event:".$id);
        foreach($users as $user){
            EventUser::insert([
                'event_id'=>$id,
                'user_id'=>$user,
                              ]);
        }
        //通过当前活动id 找出参与活动的用户 转化成数组 将用户id打乱
        $userId=EventUser::where("event_id",$id)->pluck("user_id")->shuffle();

        //找出当前的活动奖品 并随机打乱
        $prizes =EventPrize::where("event_id",$id)->get()->shuffle();
      // dd($prizes);
        //把奖品给对应的user_id
        foreach($prizes as $k=>$prize){
            //dd($prize);
            $prize->user_id=$userId[$k];
           // dd($prize->user_id);
            //得到某条用户信息
            $one =User::find( $prize->user_id);
            $name=$one->name;//得到中奖人信息
            $to =$one->email;//收件人邮箱
            $subject = '中奖通知';//邮件标题
            \Illuminate\Support\Facades\Mail::send(
                'emails.open',//视图
                compact("name"),//传递给视图的参数
                function ($message) use($to, $subject) {
                    $message->to($to)->subject($subject);
                }
            );
            //保存修改
            $prize->save();
        }
        //修改活动状态
        $event=Event::find($id);
        $event->is_prize=1;
        $event->save();
        return redirect()->back()->with("success","开奖完成");
    }



}
