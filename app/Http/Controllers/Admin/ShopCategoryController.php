<?php

namespace App\Http\Controllers\Admin;

use App\Models\Shop;
use App\Models\ShopCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ShopCategoryController extends Controller
{
    //
    public function index( ){
        $categories=ShopCategory::all();
        //视图显示
        return view("admin.cate.index",compact("categories"));
    }

    public function add(Request $request){
        //判断接收方式
        if($request->isMethod('post')){
            $this->validate( $request, [
                'name'     => 'required|unique:shop_categories',

                'img'=>"required|image"

            ] );
            //接收数据
            $data = $request->post();
            //上传图片
            $file         = $request->file( 'img' );
            $data['img'] = $file->store( 'cate_img', 'image' );
            ShopCategory::create( $data );
            //跳转
            return redirect()->route( 'cate.index' )->with( "success", "添加完成" );

            //添加


        }
        //
        return view("admin.cate.add");
    }
    //修改
    public function edit(Request $request,$id){
        //
        $cate = ShopCategory::find($id);
        if($request->isMethod('post')){
            $this->validate($request,[
                "name"=>"required",

                'img'=>"image"

            ]);
            //接收数据
            $data = $request->post();
            $file=$request->file('img');
            if($file ){
                //删除原来的数据

                Storage::delete($cate['img']);

                $data['img']=$file->store('cate_img','image');

            }

            $cate->update($data);
            //跳
            return redirect()->route( 'cate.index' )->with( "success", "修改完成" );

        }
        //显示视图
        return view("admin.cate.edit",compact("cate"));
    }
    //删除
    public function del($id){
        $shop = Shop::find($id);
        //有分类的不能删除

        $shop->delete();

    }

}
