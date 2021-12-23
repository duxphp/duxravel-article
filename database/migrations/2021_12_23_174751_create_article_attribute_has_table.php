<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticleAttributeHasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_attribute_has', function (Blueprint $table) {
            $table->integer('article_id')->nullable()->index('article_id')->comment('文章id');
            $table->integer('attr_id')->nullable()->index('attr_id')->comment('属性id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('article_attribute_has');
    }
}
