<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateFormTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('form', function (Blueprint $table) {
            $table->boolean('audit')->default(0)->nullable()->comment('审核状态')->after('search');
            $table->boolean('submit')->default(0)->nullable()->comment('外部提交')->after('audit');
            $table->integer('interval')->default(10)->nullable()->comment('提交间隔')->comment('search');
            $table->string('tpl_list')->nullable()->comment('列表模板')->after('submit');
            $table->string('tpl_info')->nullable()->comment('详情模板')->after('tpl_list');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('form', function (Blueprint $table) {
            $table->dropColumn('submit');
            $table->dropColumn('tpl_list');
            $table->dropColumn('tpl_info');
        });
    }
}
