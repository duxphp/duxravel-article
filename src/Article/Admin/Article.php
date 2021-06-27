<?php

namespace Modules\Article\Admin;

use Duxravel\Core\UI\Form;
use Duxravel\Core\UI\Table;
use Duxravel\Core\UI\Widget;
use Duxravel\Core\Util\Menu;

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

        $table->filterType('已发布', function ($model) {
            $model->where('status', 1);
        }, route('admin.article.article', ['model' => $this->modelId]))->icon(new Widget\Icon('annotation'));
        $table->filterType('草稿箱', function ($model) {
            $model->where('status', 0);
        }, route('admin.article.article', ['model' => $this->modelId, 'type' => 1]))->icon(new Widget\Icon('archive'));
        $table->filterType('回收站', function ($model) {
            $model->onlyTrashed();
        }, route('admin.article.article', ['model' => $this->modelId, 'type' => 2]))->icon(new Widget\Icon('trash'));

        $table->action()->button('添加', 'admin.article.article.page', ['model' => $this->modelId])->icon('plus');
        // 排序
        $table->model()->orderBy('article_id', 'desc');
        // 设置筛选
        $table->filter('文章', 'title', function ($query, $value) {
            $query->whereRaw(
                "MATCH(title,content) AGAINST(?)",
                [$value]
            );
        })->text('请输入文章标题')->quick();
        $table->filter('分类', 'class_id', function ($query, $value) {
            $classIds = (new \Modules\Article\Model\ArticleClass())->find($value)->descendantsAndSelf($value)->pluck('class_id');
            $query->whereHas('class', function ($query) use ($classIds) {
                $query->whereIn((new \Modules\Article\Model\ArticleClass())->getTable() . '.class_id', $classIds);
            });
        })->cascader(function ($value) {
            return \Modules\Article\Model\ArticleClass::scoped(['model_id' => $this->modelId])->orderBy('sort', 'desc')->orderBy('class_id', 'asc')->get(['class_id as id', 'parent_id as pid', 'name']);
        })->quick();
        // 设置列表
        $table->column('#', 'article_id')->width(80);
        $table->column('标题', 'title')->image('image', function ($value) {
            return $value ?: '无';
        })->desc('class', function ($value) {
            return $value->pluck('name')->toArray();
        });
        $table->column('访问/访客', 'views->pv')->color('muted')->desc('views->uv');
        $table->column('流量')->width('150')->chart();

        $column = $table->column('操作')->width('180')->align('right');
        if ($type == 2) {
            $column->link('恢复', 'admin.article.article.recovery', ['model' => $this->modelId, 'id' => 'article_id'])->type('ajax')->data(['type' => 'post']);
            $column->link('删除', 'admin.article.article.clear', ['model' => $this->modelId, 'id' => 'article_id'])->type('ajax')->data(['type' => 'post']);
        } else {
            $column->link('流量统计', 'admin.system.visitorViews.info', ['type' => \Modules\Article\Model\Article::class, 'id' => 'article_id'])->type('dialog')->attr('data-size', 'medium');
            $column->link('编辑', 'admin.article.article.page', ['model' => $this->modelId, 'id' => 'article_id']);
            $column->link('删除', 'admin.article.article.del', ['model' => $this->modelId, 'id' => 'article_id'])->type('ajax')->data(['type' => 'post']);
        }
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
        // 访问量
        if ($id) {
            $info->viewsInc();
        }
        $formId = app(\Modules\Article\Model\ArticleModel::class)->where('model_id', $this->modelId)->value('form_id');

        $form->card(function (Form $form) use ($formId, $info) {
            $form->cascader('分类', 'class_id', function () {
                return \Modules\Article\Model\ArticleClass::scoped(['model_id' => $this->modelId])->orderBy('sort', 'desc')->orderBy('class_id', 'asc')->get(['class_id as id', 'parent_id as pid', 'name']);
            }, 'class')->must()->multi();
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
                $form->text('虚拟浏览量', 'virtual_view')->type('number');
            });
            $form->editor('内容', 'content');
            $form->select('关键词', 'keyword')->tags();
            $form->textarea('描述', 'description');
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
