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
            $table->char('name', 50)->nullable()->comment('名称');
            $table->string('subname', 255)->nullable()->comment('副栏目名称');
            $table->string('tpl_content', 255)->nullable()->comment('内容模板');
            $table->string('tpl_class', 255)->nullable()->comment('分类模板');
            $table->string('image', 255)->nullable()->comment('封面图');
            $table->string('keyword', 255)->nullable()->comment('关键词');
            $table->string('description', 255)->nullable()->comment('描述');
            $table->string('url', 255)->nullable()->comment('分类跳转');
            $table->text('content')->nullable()->comment('简介');
            $table->unsignedInteger('_lft')->default(0);
            $table->unsignedInteger('_rgt')->default(0);
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
        Schema::dropIfExists('article_class');
    }
}
