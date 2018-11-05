<?php

namespace App\Http\Controllers\Shop;

use App\Models\Shop;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IndexController extends BaseController
{
    //显示后台
    public function index(  ){


        return view("shop.index.index");
    }
    
    //获得每月总收入
    public function toralAll(  ){
        
    }

}
