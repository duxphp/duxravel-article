<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticleClassTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_class', function (Blueprint $table) {
            $table->bigIncrements('class_id');
            $table->integer('parent_id')->nullable()->comment('上级id');
            $table->integer('model_id')->default(0)->comment('模型id');
            $table->char('name', 50)->nullable()->default('')->comment('名称');
            $table->integer('sort')->default(0)->comment('顺序');
            $table->unsignedInteger('_lft')->default(0);
            $table->unsignedInteger('_rgt')->default(0);
            $table->integer('create_time')->nullable()->default(0);
            $table->integer('update_time')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('article_class');
    }
}
