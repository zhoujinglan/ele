<?php

namespace App\Http\Controllers\Shop;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    //显示后台
    public function index(  ){
         return view("shop.index.index");
    }

}
