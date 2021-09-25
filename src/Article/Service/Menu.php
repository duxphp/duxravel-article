<?php

namespace Modules\Article\Service;

/**
 * 系统菜单接口
 */

class Menu
{
    /**
     * 获取菜单结构
     */
    public function getAdminMenu()
    {
        $model = app(\Modules\Article\Model\ArticleModel::class)->get();

        $menuList = $model->map(function ($item) {
            return [
                'name' => $item['name'] . '管理',
                'url' => 'admin.article.article',
                'params' => ['model' => $item['model_id']]
            ];
        })->toArray();

        return [
            'article' => [
                'name'  => '文章',
                'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
</svg>',
                'order' => 10,
                'menu'  => [
                    [
                        'name'  => '内容管理',
                        'order' => 1,
                        'menu' => $menuList
                    ],
                    [
                        'name'  => '内容设置',
                        'order' => 100,
                        'menu'  => [
                            [
                                'name'  => '模型管理',
                                'url'   => 'admin.article.articleModel',
                            ],
                            [
                                'name'  => '内容属性',
                                'url'   => 'admin.article.attribute',
                            ],
                            // Generate Menu Make
                        ]
                    ],
                ],
            ],

        ];
    }
}

