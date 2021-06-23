<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisitorViewsDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visitor_views_data', function (Blueprint $table) {
            $table->increments('data_id');
            $table->string('has_type', 250)->nullable()->default('')->index('has_type')->comment('关联类型');
            $table->integer('has_id')->default(0)->index('has_id')->comment('关联id');
            $table->char('driver', 10)->nullable()->default('')->index('driver')->comment('设备');
            $table->char('date', 8)->nullable()->index('date')->comment('日期');
            $table->integer('pv')->default(1)->comment('浏览量');
            $table->integer('uv')->default(0)->comment('访客量');
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
        Schema::dropIfExists('visitor_views_data');
    }
}
