<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment("用户名");
            $table->string('tel')->unique()->comment("手机号");
            $table->string("provence")->comment("省份");
            $table->string("city")->comment("市");
            $table->string("area")->comment("区");
            $table->string("detail_address")->comment("详细地址");
            $table->integer("user_id")->comment("登录用户id");

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
        Schema::dropIfExists('addresses');
    }
}
