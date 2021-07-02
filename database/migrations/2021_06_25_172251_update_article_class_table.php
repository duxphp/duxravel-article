<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateArticleClassTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('article_class', function (Blueprint $table) {
            $table->string('image', 250)->nullable()->comment('封面图')->after('name');
            $table->string('tpl_class', 250)->nullable()->comment('分类模板')->after('name');
            $table->string('tpl_content', 250)->nullable()->comment('内容模板')->after('name');
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
            $table->dropColumn('image');
            $table->dropColumn('tpl_class');
            $table->dropColumn('tpl_content');
        });
    }
}