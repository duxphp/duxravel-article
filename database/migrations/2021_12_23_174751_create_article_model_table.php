<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticleModelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_model', function (Blueprint $table) {
            $table->increments('model_id');
            $table->char('name', 100)->nullable()->default('')->comment('模型名');
            $table->string('tpl_content', 250)->nullable()->comment('内容模板');
            $table->string('tpl_class', 250)->nullable()->comment('分类模板');
            $table->integer('form_id')->nullable()->comment('表单id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('article_model');
    }
}
