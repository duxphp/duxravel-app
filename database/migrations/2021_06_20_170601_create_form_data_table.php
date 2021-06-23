<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_data', function (Blueprint $table) {
            $table->increments('data_id');
            $table->integer('form_id')->default(0)->index('form_id')->comment('表单id');
            $table->string('has_type', 250)->nullable()->default('')->index('has_type')->comment('关联类型');
            $table->integer('has_id')->default(0)->index('has_id')->comment('关联id');
            $table->longText('data')->nullable()->comment('表单内容');
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
        Schema::dropIfExists('form_data');
    }
}
