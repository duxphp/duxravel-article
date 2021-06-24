<?php

namespace Duxravel\Article\Seeders;

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
        

        \DB::table('article_model')->delete();
        
        \DB::table('article_model')->insert(array (
            0 => 
            array (
                'model_id' => 1,
                'name' => '文章',
                'form_id' => 0,
            ),
            1 => 
            array (
                'model_id' => 2,
                'name' => '视频',
                'form_id' => 1,
            ),
        ));
        
        
    }
}