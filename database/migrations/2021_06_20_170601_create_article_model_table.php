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
