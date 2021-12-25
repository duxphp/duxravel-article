<?php

namespace Modules\Article\Seeders;

use Illuminate\Database\Seeder;

class ArticleModelTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('article_model')->insert([
            [
                'name' => '文章',
                'form_id' => 0,
            ],
        ]);


    }
}