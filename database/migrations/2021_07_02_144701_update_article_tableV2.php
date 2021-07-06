<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateArticleTableV2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('article', function (Blueprint $table) {
            $table->string('source', 250)->nullable()->comment('文章来源')->after('auth');
            $table->integer('sort')->nullable()->default(0)->comment('自定义顺序')->after('status');
            $table->integer('release_time')->nullable()->default(0)->comment('自定义发布时间')->after('sort');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('article', function (Blueprint $table) {
            $table->dropColumn('source');
            $table->dropColumn('sort');
            $table->dropColumn('release_time');
        });
    }
}
