<?php

namespace Modules\Article\Admin;

use Duxravel\Core\UI\Form;
use Duxravel\Core\UI\Node;
use Duxravel\Core\UI\Table;
use Duxravel\Core\UI\Widget\Link;
use Duxravel\Core\UI\Widget\TreeList;
use \Modules\Article\Model\ArticleClass;

class Article extends ArticleExpend
{

    public string $model = \Modules\Article\Model\Article::class;

    /**
     * @return Table
     * @throws \Exception
     */
    protected function table(): Table
    {
        $type = request()->get('type');
        $table = new Table(new $this->model());
        $table->title('内容管理');
        $table->model()->where('model_id', $this->modelId);
        $table->model()->with('class');


        $table->action()->button('添加', 'admin.article.article.page', ['model' => $this->modelId]);

        $table->filterType('已发布', function ($model) {
            $model->where('status', 1);
        })->icon('annotation');
        $table->filterType('草稿箱', function ($model) {
            $model->where('status', 0);
        })->icon('archive');
        $table->filterType('回收站', function ($model) {
            $model->onlyTrashed();
        })->icon('trash');
        // 排序
        $table->model()->orderBy('article_id', 'desc');
        // 设置筛选
        $table->filter('文章', 'title', function ($query, $value) {
            $query->whereRaw(
                "MATCH(title,content) AGAINST(?)",
                [$value]
            );
        })->text('请输入文章标题')->quick();

        $table->column('#', 'article_id')->width(80);


        $table->column('标题', 'title')->image('image', function ($value) {
            return $value ?: '无';
        })->desc('class', function ($value) {
            return $value->pluck('name')->toArray();
        });
        $table->column('访问/访客', 'views->pv')->color('muted')->desc('views->uv');
        //$table->column('流量')->width('150')->chart();

//        $table->column('状态', 'status')->toggle('status', 'admin.article.article.status', ['model' => $this->modelId, 'id' => 'article_id'])->width(100);

        $column = $table->column('操作')->width('180')->align('right')->width(200)->fixed();
        if ($type == 2) {
            $column->link('恢复', 'admin.article.article.recovery', ['model' => $this->modelId, 'id' => 'article_id'])->type('ajax', ['method' => 'post']);
            $column->link('删除', 'admin.article.article.clear', ['model' => $this->modelId, 'id' => 'article_id'])->type('ajax', ['method' => 'post']);
        } else {
            $column->link('预览', 'web.article.info', ['id' => 'article_id'])->attr('target', '_blank');
            $column->link('流量', 'admin.system.visitorViews.info', ['type' => \Modules\Article\Model\Article::class, 'id' => 'article_id'])->type('dialog');
            $column->link('编辑', 'admin.article.article.page', ['model' => $this->modelId, 'id' => 'article_id']);
            $column->link('删除', 'admin.article.article.del', ['model' => $this->modelId, 'id' => 'article_id'])->type('ajax', ['method' => 'post']);
        }

        $table->filter('分类id', 'class_id', function ($query, $value) {
            $classIds = ArticleClass::find($value)->descendantsAndSelf($value)->pluck('class_id');
            $query->whereHas('class', function ($query) use ($classIds) {
                $query->whereIn((new ArticleClass)->getTable() . '.class_id', $classIds);
            });
        });

        $table->side(function () {
            return (new Node())->div(function ($node) {
                $node->div((new Link('添加分类', 'admin.article.articleClass.page', ['model' => $this->modelId, 'class_id' => '{data.filter.class_id}']))->button('primary', 'medium', true)->icon('plus')->type('dialog')->getRender())->class('p-2 flex-none');
                $node->div(
                    (new TreeList(request()->get('class_id'), 'class_id'))
                        ->search(true)
                        ->url(route('admin.article.articleClass.ajax', ['model' => $this->modelId]))
                        ->sortUrl(route('admin.article.articleClass.sortable', ['model' => $this->modelId]))
                        ->menu([
                            'add' => [
                                'name' => '新增',
                                'url' => app_route('admin.article.articleClass.page', ['class_id' => '{item.rawData.class_id}', 'model' => $this->modelId], false),
                                'type' => 'dialog',
                            ],
                            'edit' => [
                                'name' => '编辑',
                                'url' => app_route('admin.article.articleClass.page', ['id' => '{item.rawData.class_id}', 'model' => $this->modelId], false),
                                'type' => 'dialog',
                            ],
                            'del' => [
                                'name' => '删除',
                                'url' => app_route('admin.article.articleClass.del', ['id' => '{item.rawData.class_id}', 'model' => $this->modelId], false),
                                'type' => 'ajax',
                            ],
                        ])
                        ->render()
                )->class('p-2 h-10 flex-grow');
            })->class('h-screen flex flex-col')->render();
        }, 'left', true, '300px');


        return $table;
    }

    /**
     * @param null $id
     * @return Form
     */
    public function form($id = null): \Duxravel\Core\UI\Form
    {
        $model = new $this->model();
        $form = new \Duxravel\Core\UI\Form($model);
        $form->title('文章信息');
        $form->action(route('admin.article.article.save', ['model' => $this->modelId, 'id' => $id]));

        $info = $model->find($id);
        $formId = app(\Modules\Article\Model\ArticleModel::class)->where('model_id', $this->modelId)->value('form_id');

        $form->card(function (Form $form) use ($formId, $info) {
            $form->cascader('分类', 'class_id', function () {
                return \Modules\Article\Model\ArticleClass::scoped(['model_id' => $this->modelId])->defaultOrder()->get(['class_id as id', 'parent_id as pid', 'name']);
            }, 'class')->must()->leaf(false)->multi()->default(request()->get('class_id'));
            $form->text('标题', 'title');
            $form->text('副标题', 'subtitle');

            $form->checkbox('属性', 'attrs', function () {
                return \Modules\Article\Model\ArticleAttribute::orderBy('attr_id', 'asc')->pluck('name', 'attr_id');
            }, 'attribute');
            $form->image('封面', 'image');
            // 设置表单元素
            if ($formId) {
                app(\Duxravel\Core\Util\Form::class)->getFormUI($formId, $form, $info['article_id'], \Modules\Article\Model\Article::class);
            }
            $row = $form->row();
            $row->column(function ($form) {
                $form->text('作者', 'auth');
            });
            $row->column(function ($form) {
                $form->text('来源', 'source');
            });
            $row->column(function ($form) {
                $form->text('虚拟浏览量', 'virtual_view')->type('number');
            });
            $form->editor('内容', 'content');
            $form->tags('关键词', 'keyword');
            $form->textarea('描述', 'description');
            $form->datetime('发布时间', 'release_time')->default(time());
            $form->text('排序', 'sort')->type('number');
            $form->radio('状态', 'status', [
                1 => '发布',
                0 => '草稿箱',
            ]);
        });

        // 表单保存
        $form->before(function ($data, $type, $model) {
            $model->model_id = $this->modelId;
            if (!$data['description']) {
                $model->description = html_text($data['content'], 250);
            }
        });
        $form->after(function ($data, $type, $model) use ($formId) {
            $model->formSave($formId, $data);
            $model->retag($data['keyword']);
        });

        return $form;
    }

    public function dataSearch(): array
    {
        return ['title', 'subtitle'];
    }

    public function dataField(): array
    {
        return ['title', 'image', 'description as desc', 'model_id'];
    }

    public function dataWhere($query)
    {
        return $query->where('model_id', $this->modelId);
    }

    public function dataManageUrl($item): string
    {
        return route('admin.article.article.page', ['model' => $item['model_id'], 'id' => $item['id']]);
    }

}
