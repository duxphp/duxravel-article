<?php

namespace Modules\Article\Admin;

class ArticleExpend extends \Modules\System\Admin\Expend
{
    public int $modelId;

    public function index($modelId = 0)
    {
        $this->modelId = $modelId;
        return parent::index();
    }

    public function ajax($modelId = 0)
    {
        $this->modelId = $modelId;
        return parent::ajax();
    }

    public function add($modelId = 0)
    {
        $this->modelId = $modelId;
        return parent::add();
    }

    public function edit($modelId = 0, $id = 0)
    {
        $this->modelId = $modelId;
        return parent::edit($id);
    }

    public function page($modelId = 0, $id = 0)
    {
        $this->modelId = $modelId;
        return parent::page($id);
    }

    public function save($modelId = 0, $id = 0)
    {
        $this->modelId = $modelId;
        return parent::save($id);
    }

    public function del($modelId = 0, $id = 0)
    {
        $this->modelId = $modelId;
        return parent::del($id);
    }

    public function recovery($modelId = 0, $id = 0)
    {
        $this->modelId = $modelId;
        return parent::recovery($id);
    }

    public function clear($modelId = 0, $id = 0)
    {
        $this->modelId = $modelId;
        return parent::clear($id);
    }

    public function data($modelId = 0)
    {
        $this->modelId = $modelId;
        return parent::data();
    }

    public function status($modelId = 0, $id = 0)
    {
        $this->modelId = $modelId;
        return parent::status($id);
    }
}
