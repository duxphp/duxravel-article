<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article', function (Blueprint $table) {
            $table->increments('article_id');
            $table->integer('model_id')->default(0)->comment('模型id');
            $table->string('title', 250)->nullable()->default('')->comment('标题');
            $table->string('keyword', 250)->nullable()->default('')->comment('关键词');
            $table->string('description', 250)->nullable()->default('')->comment('描述');
            $table->string('image', 250)->nullable()->default('')->comment('封面图');
            $table->string('auth', 50)->nullable()->default('')->comment('作者');
            $table->longText('content')->nullable()->comment('内容');
            $table->integer('virtual_view')->nullable()->default(0)->comment('虚拟浏览量');
            $table->boolean('status')->default(1)->index('status')->comment('状态');
            $table->integer('create_time')->nullable();
            $table->integer('update_time')->nullable();
            $table->integer('delete_time')->nullable();
        });

        DB::statement('ALTER TABLE `' . env('DB_TABLE_PREFIX', '') . 'article` ADD FULLTEXT KEY `keyword` (`title`,`content`) WITH PARSER `ngram` ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('article');
    }
}
