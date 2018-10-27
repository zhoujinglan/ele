<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuCategoryModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_category_models', function (Blueprint $table) {
            $table->increments('id');
            $table->string("name")->unique()->comment("分类名称");
            $table->string("type_accumulation")->comment("菜品编号（a-z前端使用）");
            $table->integer("shop_id")->comment("所属商家ID");
            $table->string("description")->comment("描述");
            $table->boolean("is_selected")->comment("是否默认分类");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu_category_models');
    }
}
