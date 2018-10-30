<?php

namespace App\Http\Controllers\Shop;

use App\Models\Menu;
use App\Models\MenuCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

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
        $cates=MenuCategory::where("shop_id",Auth::user()->shop->id)->get();
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
                "status" => "required",

            ] );
            $data=$request->post();
            $data['shop_id']=Auth::user()->shop->id;

           Menu::create($data);
            //跳转
            return redirect()->route("menu.index")->with("success","添加成功");

        }

        //显示视图
        //查询所有分类
        $cates = MenuCategory::where("shop_id",Auth::user()->shop->id)->get();
        return view("shop.menu.add",compact("cates"));

    }
    //修改

    public function edit(Request $request,$id){
        $menu=Menu::find($id);
       // dd($menu);
        //判断接收方式
        if($request->isMethod("post")){
            $this->validate( $request, [
                //'goods_name'=> 'required |unique:menus,goods_name'.$id,
                'goods_name'=>[
                  'required',
                  Rule::unique("menus")->ignore($id)
                ],
                "category_id" => "required",
                "goods_price" => "required",
                "status" => "required",

            ] );

            $data =$request->post();
            //删除原来的图片
           // dd($data);
            Storage::disk("oss")->delete($menu->goods_img);

            $menu->update($data);
            //修改成功跳转
            return redirect()->route("menu.index")->with("success","修改成功");
        }
        //回显

        $cates=MenuCategory::where("shop_id",Auth::user()->shop->id)->get();
        //显示视图
        return view("shop.menu.edit",compact("menu","cates"));
    }
    
    //添加图片的方法
    public function upload(Request $request){

        //上传处理
        $file=$request->file("file");//内部的文件  没有在html中显示
        if($file){
            //有文件进行上传
            $url = $file->store("menu");
            //得到真实地址  把http加载进去
           // $url = Storage::url($url);
            $data['url']=$url;
            return $data;
        }

    }
    //删除
    public function del($id){
        $menu = Menu::find($id);
        Storage::disk("oss")->delete($menu->goods_img);
        $menu->delete();
        //跳转
        return redirect()->route("menu.index")->with("success","修改成功");

    }
}
