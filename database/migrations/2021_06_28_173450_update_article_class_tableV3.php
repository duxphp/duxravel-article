<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateArticleClassTableV3 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('article_class', function (Blueprint $table) {
            $table->string('keyword', 250)->nullable()->default('')->comment('关键词')->after('image');
            $table->string('description', 250)->nullable()->default('')->comment('描述')->after('keyword');
            $table->text('content')->nullable()->comment('简介')->after('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('article_class', function (Blueprint $table) {
            $table->dropColumn('content');
        });
    }
}
