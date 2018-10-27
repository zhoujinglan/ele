<?php

namespace App\Http\Controllers\Shop;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class BaseController extends Controller
{
    //
   public function __construct()
    {

       $this->middleware("auth",[
           "except"=>["login","reg","apply","logout"] ]);

   }

}
