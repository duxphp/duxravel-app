<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form', function (Blueprint $table) {
            $table->increments('form_id');
            $table->char('name', 100)->nullable()->default('')->comment('表单名称');
            $table->string('description', 250)->nullable()->default('')->comment('表单描述');
            $table->char('menu', 50)->nullable()->default('')->comment('菜单名');
            $table->longText('data')->nullable()->comment('表单配置');
            $table->boolean('manage')->nullable()->default(0)->comment('独立管理');
            $table->char('search', 50)->nullable()->default('')->comment('搜索字段');
            $table->integer('create_time')->default(0);
            $table->integer('update_time')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('form');
    }
}
