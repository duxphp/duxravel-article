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
        Schema::table('article_model', function (Blueprint $table) {
            $table->string('tpl_class')->nullable()->after('name');
            $table->string('tpl_content')->nullable()->after('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('article_model', function (Blueprint $table) {
            $table->dropColumn('tpl_class');
            $table->dropColumn('tpl_content');
        });
    }
}
