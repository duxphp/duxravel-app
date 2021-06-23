<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisitorOperateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visitor_operate', function (Blueprint $table) {
            $table->increments('operate_id');
            $table->char('has_type', 50)->nullable()->default('')->index('has_type')->comment('关联类型');
            $table->integer('has_id')->default(0)->index('has_id')->comment('关联id');
            $table->string('username', 100)->nullable()->default('')->index('username')->comment('用户名');
            $table->char('method', 6)->nullable()->default('')->index('method')->comment('动作');
            $table->string('route', 250)->nullable()->default('')->index('route')->comment('路由');
            $table->char('name', 50)->nullable()->default('')->index('name')->comment('名称');
            $table->char('desc', 50)->nullable()->default('')->comment('描述');
            $table->text('params')->nullable()->comment('参数');
            $table->string('ip', 45)->nullable()->default('')->comment('ip');
            $table->string('ua', 250)->nullable()->default('')->comment('ua');
            $table->char('browser', 50)->nullable()->default('')->comment('浏览器');
            $table->char('device', 50)->nullable()->default('')->comment('设备');
            $table->boolean('mobile')->default(0)->comment('移动端');
            $table->char('time', 30)->default('0')->comment('记录时间');
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
        Schema::dropIfExists('visitor_operate');
    }
}
