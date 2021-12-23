<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticleClassHasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_class_has', function (Blueprint $table) {
            $table->integer('article_id')->nullable()->index('article_id')->comment('文章id');
            $table->integer('class_id')->nullable()->index('class_id')->comment('栏目id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('article_class_has');
    }
}
