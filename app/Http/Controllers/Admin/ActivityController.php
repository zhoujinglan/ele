<?php

namespace App\Http\Controllers\Admin;

use App\Models\Activity;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ActivityController extends Controller
{


    //显示活动
    public function index(Request $request ){
        //$activities=Activity::where("end_time",">=",date('Y-m-d H:i:s', time()))->get();
       // dd($activities[0]->start_time);
        //dd(date('Y-m-d H:i:s', time()));//转成当前时间
        //搜索
       $url = $request->query();
       $time = $request->get("time");
       $content = $request->get("keyword");
       //拼接查询条件
        $query = Activity::orderBy("id");
        //得到当前时间
        $nowTime=date('Y-m-d H:i:s', time());
        //判断时间  1 进行 2 结束 3 未开始
        if( $time == 1 ){
            $query->where("start_time","<=",$nowTime)->where("end_time",">",$nowTime);
        }
        if($time == 2){
            $query->where("end_time","<",$nowTime);
        }
        if($time == 3){
            $query->where("start_time",">",$nowTime);
        }
        //内容搜索
        if($content !== null){
            $query->where("title","like","%{$content}%")->orWhere("content","like","%{$content}%");
        }

        $activities = $query->paginate(2);

        //$activities =Activity::all();
        //引入视图
        return view("admin.activity.index",compact("activities","url"));

    }


    //添加活动
    public function add(Request $request){

        //判断接收方式
        if($request->isMethod('post')){
            //验证
            $data = $request->post();
            //dd($data);
            Activity::create($data);
            //添加成功跳转
            return redirect()->route("activity.index")->with("success","添加成功");

        }
        //显示视图
        return view("admin.activity.add");
    }
    //修改
    public function edit(Request $request,$id){
        //找到这一条
        $activity = Activity::find($id);
        //判断
        if($request->isMethod("post")){
            //验证
            //修改
            $data = $request->post();
            $activity->update($data);
            //跳转
            return redirect()->route("activity.index")->with("success","修改成功");
        }
        //显示视图
        return view("admin.activity.edit",compact("activity"));
    }
}
