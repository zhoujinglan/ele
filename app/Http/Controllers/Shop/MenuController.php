<?php

namespace App\Http\Controllers\Shop;

use App\Models\Menu;
use App\Models\MenuCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class MenuController extends Controller
{
    //
    public function index(Request $request){
        //分页
        //接收参数
        $url =$request->query();
//        dd($url);


        $cateId = $request->get('category_id');
       //dd($cateId);
        $minPrice = $request->get('minPrice');
        $maxPrice = $request->get('maxPrice');
        $keyword = $request->get('keyword');
        //得到所有并要有分页

       // $query=Menu::orderBy("id");
         $query=Menu::orderBy("id");
        if ($keyword!==null){

            $query->where("goods_name","like","%{$keyword}%");
        }
        if($minPrice !== null  ){
            $query->where("goods_price",">=",$minPrice);
        }
        if($maxPrice !== null){
            $query->where("goods_price","<=",$maxPrice);
        }

        if ($cateId!==null){
            $query->where("category_id",$cateId);
        }

        //$menus =Menu::all();
        $menus =$query->where("shop_id",Auth::user()->shop->id)->paginate(3);

        // 获得菜品分类
        $cates=MenuCategory::all();
      //显示视图
        return view("shop.menu.index",compact("menus","cates","url"));
    }

    //添加
    public function add(Request $request){
       //判断接收方式
        $id=Auth::id();
        if($request->isMethod("post")){
            $this->validate( $request, [
                'goods_name'=> 'required |unique:menus',
                "category_id" => "required",
                "description" => "required",
                "goods_price" => "required",
                "img" => "required",
                "status" => "required",

            ] );
            $data=$request->post();
            $data['shop_id']=Auth::user()->shop->id;
            $data['goods_img']=$request->file("img")->store("menu");
            //添加


           Menu::create($data);
            //跳转
            return redirect()->route("menu.index")->with("success","添加成功");

        }

        //显示视图
        //查询所有分类
        $cates = MenuCategory::all();
        return view("shop.menu.add",compact("cates"));

    }
    //修改

    public function edit(Request $request,$id){
        $menu=Menu::find($id);
       // dd($menu);
        //判断接收方式
        if($request->isMethod("post")){
            $this->validate( $request, [
                'goods_name'=> 'required ',
                "category_id" => "required",
                "goods_price" => "required",
                "status" => "required",

            ] );
            $data=$request->post();
            $file=$request->file("img");
            //判断图片
            if($file){
                //有图片删除原来的图片
                @unlink($menu->goods_img);
                $data['goods_img']=$file->store("menu");
            }

            $menu->update($data);
            //修改成功跳转
            return redirect()->route("menu.index")->with("success","修改成功");
        }
        //回显

        $cates=MenuCategory::all();
        //显示视图
        return view("shop.menu.edit",compact("menu","cates"));
    }
}
