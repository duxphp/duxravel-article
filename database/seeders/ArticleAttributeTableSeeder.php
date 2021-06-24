<?php

namespace Duxravel\Article\Seeders;

use Illuminate\Database\Seeder;

class ArticleAttributeTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('article_attribute')->delete();
        
        \DB::table('article_attribute')->insert(array (
            0 => 
            array (
                'attr_id' => 1,
                'name' => '置顶',
                'create_time' => 1624024482,
                'update_time' => 1624024482,
            ),
            1 => 
            array (
                'attr_id' => 2,
                'name' => '热门',
                'create_time' => 1624024856,
                'update_time' => 1624026827,
            ),
            2 => 
            array (
                'attr_id' => 3,
                'name' => '推荐',
                'create_time' => 1624024867,
                'update_time' => 1624024867,
            ),
            3 => 
            array (
                'attr_id' => 4,
                'name' => '精华',
                'create_time' => 1624024874,
                'update_time' => 1624026871,
            ),
        ));
        
        
    }
}