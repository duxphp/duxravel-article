<?php

namespace Modules\Article\Admin;

use Duxravel\Core\UI\Form;
use Duxravel\Core\UI\Table;

class ArticleModel extends \Modules\System\Admin\Expend
{

    public string $model = \Modules\Article\Model\ArticleModel::class;

    protected function table(): Table
    {

        $table = new Table(new $this->model());
        $table->title('模型管理');

        $table->filter('模型', 'name', function ($query, $value) {
            $query->where('name', 'like', '%' . $value . '%');
        })->text('请输入模型名称')->quick();

        $table->action()->button('添加', 'admin.article.articleModel.page')->type('dialog');
        $table->column('#', 'model_id')->width(80);
        $table->column('模型', 'name');
        $column = $table->column('操作')->width(200);
        $column->link('编辑', 'admin.article.articleModel.page', ['id' => 'model_id'])->type('dialog');
        $column->link('删除', 'admin.article.articleModel.del', ['id' => 'model_id'])->type('ajax')->data(['type' => 'post']);

        return $table;
    }

    public function form(int $id = 0): Form
    {
        $modelId = request()->get('model_id');
        $form = new Form(new $this->model());
        $form->dialog(true);

        $form->text('模型名称', 'name')->verify([
            'required',
        ], [
            'required' => '请填写模型名称',
        ]);
        $form->text('分类模板', 'tpl_class')->afterText('.blade.php');
        $form->text('内容模板', 'tpl_content')->afterText('.blade.php');

        $form->select('表单绑定', 'form_id', function () {
            return  \Duxravel\Core\Model\Form::get()->prepend([
                'form_id' => 0,
                'name' => '暂不绑定'
            ])->pluck('name', 'form_id')->toArray();
        })->default($modelId);

        return $form;
    }

}
