<?php

namespace Modules\Article\Admin;

use Duxravel\Core\Util\Tree;
use Duxravel\Core\UI\Form;
use Duxravel\Core\UI\Table;

class ArticleClass extends ArticleExpend
{

    public string $model = \Modules\Article\Model\ArticleClass::class;


    protected function table(): Table
    {
        $table = new Table(new $this->model());
        $table->title('分类管理');
        $table->model()->scoped(['model_id' => $this->modelId])->orderBy('sort', 'desc')->orderBy('model_id', 'asc');
        $table->tree();

        $table->filter('分类', 'name', function ($query, $value) {
            $query->where('name', 'like', '%' . $value . '%');
        })->text('请输入分类名称')->quick();

        $table->action()->button('添加', 'admin.article.articleClass.page', ['model' => $this->modelId])->type('dialog');

        $table->column('分类', 'name', function ($value, $row) {
            return $value . '<span class="text-gray-500 ml-2">[' . $row['class_id'] . ']<span>';
        });

        $table->column('顺序', 'sort')->width(150)->input('sort', ['admin.article.articleClass.status', ['model' => $this->modelId]], ['id' => 'class_id']);

        $column = $table->column('操作')->width(150);
        $column->link('新增', 'admin.article.articleClass.page', ['model' => $this->modelId, 'class_id' => 'class_id'])->type('dialog');
        $column->link('编辑', 'admin.article.articleClass.page', ['model' => $this->modelId, 'id' => 'class_id'])->type('dialog');
        $column->link('删除', 'admin.article.articleClass.del', ['model' => $this->modelId, 'id' => 'class_id'])->type('ajax')->data(['type' => 'post']);

        return $table;
    }

    public function form(?int $id = 0): Form
    {
        $classId = request()->get('class_id');
        $form = new Form(new $this->model());
        $form->dialog(true);
        $form->action(route('admin.article.articleClass.save', ['model' => $this->modelId, 'id' => $id]));
        $form->cascader('上级分类', 'parent_id', function ($value) {
            return $this->model::scoped(['model_id' => $this->modelId])->orderBy('sort', 'desc')->orderBy('class_id', 'asc')->get(['class_id as id', 'parent_id as pid', 'name']);
        })->default($classId);
        $form->text('分类名称', 'name')->verify([
            'required',
        ], [
            'required' => '请填写分类名称',
        ]);
        $form->text('分类副名称', 'subname');
        $form->image('封面图', 'image');
        $form->text('分类关键词', 'keyword');
        $form->text('分类简介', 'description');
        $form->textarea('分类简介', 'text');
        $form->text('分类模板', 'tpl_class');
        $form->text('内容模板', 'tpl_content');

        $form->front(function ($data, $type, $model) {
            $model->model_id = $this->modelId;
            return $model;
        });
        return $form;
    }

}
