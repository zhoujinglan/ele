<?php

namespace App\Http\Controllers\Shop;

use App\Models\Menu;
use App\Models\MenuCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class MenuCategoryController extends Controller
{
    //显示所有
    public function index( ){
        $categories=MenuCategory::where("shop_id",Auth::user()->shop->id)->get();//shop是一个方法
        //
        return view("shop.menu_cate.index",compact("categories"));
    }
    //添加
    public function add(Request $request){
       //判断接收方式

        if($request->isMethod("post")){
            $data= $this->validate( $request, [
                'name'=> 'required |unique:menu_categories',
                "type_accumulation" => "required",
                "description" => "required",
                "is_selected" => "required",
            ] );
            $id=Auth::user()->id;
            //dd($id);
            $data['shop_id']=$id;
            $id=Auth::id();
            if($data['is_selected']){
                MenuCategory::where("shop_id",$id)->update(['is_selected'=>0]);
            }
            //添加
            MenuCategory::create($data);
            //跳转
            return redirect()->route("menu_cate.index")->with("success","添加成功");

        }

        //显示视图
        return view("shop.menu_cate.add");
    }

    //修改
    public function edit(Request $request,$id){
        $cate=MenuCategory::find($id);
//        dd($cate);
        //判断接收方式
        if($request->isMethod("post")){
            $this->validate( $request, [
                'name'=> 'required',
                "type_accumulation" => "required",
                "description" => "required",

            ] );

           $data =$request->post();
           //dd($data);
            if($data['is_selected'] == 1){
                 MenuCategory::where("shop_id",$cate->shop_id)->update(['is_selected'=>0]);
                // dd($a);
            }
            $cate->update($data);
            //跳转
            return redirect()->route("menu_cate.index")->with("success","修改成功");


        }
        //显示图片
        return view("shop.menu_cate.edit",compact("cate"));

    }
    //删除
    public function del($id){
     $cate=MenuCategory::find($id);
     //判断有菜品不能删除
        //得到当前分类对应的店铺数
        $num=Menu::where('shop_category_id',$cate->id)->count();
        // dd($num);
        //判断当前分类店铺数
        if ($num){
            //回跳
            return  back()->with("danger","有菜品 的分类不能删除");
        }
        //否则删除
        $cate->delete();
        //跳转
        return redirect()->route('menu_cate.index')->with('success',"删除成功");



    }
}
