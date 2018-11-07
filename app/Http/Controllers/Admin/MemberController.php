<?php

namespace App\Http\Controllers\Admin;

use App\Models\Member;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MemberController extends Controller
{
    //
    public function index(  ){
        //查询出所有的会员信息
        $members = Member::paginate(3);
        //dd($members);
        return view("admin.member.index",compact("members"));
    }
}
