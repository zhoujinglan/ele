<?php

namespace App\Http\Controllers\Api;

use App\Models\Member;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Mrgoon\AliSms\AliSms;

class MemberController extends Controller
{
    //注册
    public function reg( Request $request ){
     //验证
        $this->validate($request,[
            'username' =>'required ',
            "password" => "required|unique:members",
        ]);

        $data= $request->post();
      $code =  Redis::get('tel_'.$data['tel']);
        if($data['sms'] == $code){
            $data['password'] = Hash::make($data['password']);
            if( Member::create($data)){
                $data= [
                    'status'=>"true",
                    'message'=>"注册成功 请登录",
                ];
            }else{
                $data= [
                    'status'=>"false",
                    'message'=>"注册失败",

                ];
            }
            return $data;

        }



    }
    //获得验证码
    public function sms(Request $request  ){
         //1.接收参数
        $tel = $request->get("tel");
        //2.随机生成验证码
        $code = mt_rand(1000,9999);
        //3.把验证码保存起来（redis  文件保存）
        Redis::set("tel_".$tel,$code);//先保存
        Redis::expire('tel_'.$tel,60*10);//设置多久时间失效  验证码重发

        //4. 把验证码发给手机 用到阿里云短信服务
        $config = [
            'access_key' => env("ALIYUNU_ACCESS_ID"),//appid
            'access_secret' =>env("ALIYUNU_ACCESS_KEY"),//阿里云appkey
            'sign_name' => env('ALIYUN_SIGN_NAME'),//签名
        ];

        $sms = new AliSms();
        $response = $sms->sendSms($tel, 'SMS_149422431', ['code'=> $code], $config);
        //5.返回
        //if($response->code== 'OK'){}
        $data=[
          'status'=>true,
          'message'=>"获取短信验证码成功".$code,


        ];
        return $data;

    }

    public function login(Request $request){
        $username = $request->post('name');
        $password = $request->post('password');
        $member = Member::where('username',$username)->first();
//        dd($member);
        if( Hash::check($password,$member->password)){
            $data=[
                'status'=>"true",
                'message'=>"登录成功",
                'user_id'=>$member->id,
                'username'=>$username,

            ];
        }else{
            $data=[
                'status'=>"false",
                'message'=>"登录失败 密码或者账号有误",

            ];
        }
        return $data;

    }
    //密码重置
    public function reset(Request $request ){
        //得到传送的值
        $data = $request->post();
        //得到验证码
        $code =  Redis::get('tel_'.$data['tel']);
        //判断验证码是否输入是否正确
        if($code == $data["sms"]){
            //通过tel查询一条数据
            $tel = $data['tel'];
            $member = Member::where('tel',$tel)->first();
            $data['password'] = Hash::make($data['password']);
            if( $member->update($data) ){
                $data=[
                    'status'=>"true",
                    'message'=>"修改成功",

                ];
            }else{
                $data=[
                    'status'=>"false",
                    'message'=>"重置失败",


                ];
            }
            return $data;

        }


    }

    //修改密码
    public function change( Request $request){
        //$data=$request->post();
        //dd($data['id']);
        $data = $this->validate($request,[
            "oldPassword"=>"required",
            "newPassword"=>"required",
            "id"=>"required"


        ]);

        //旧密码和数据库密码对比
        $oldPassword = $request->post('oldPassword');
        $rePassword =$request->post('newPassword');
        //加密
        $new = Hash::make($rePassword);
        $member = Member::where("id", $data['id'])->first();

        //hash旧密码对比
        if(Hash::check($oldPassword, $member->password)){
            //修改新密码
            Member::where('id',$data['id'])->update(['password'=>$new]);
            $data=[
                "status"=>"true",
                "message"=>"新密码正确",
            ];
        }else{
            $data=[
                "status"=>"false",
                "message"=>"old旧密码错误",
            ];
        }

        return $data;


    }


}