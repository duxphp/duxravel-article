<?php

namespace Modules\Article\Admin;

class ArticleExpend extends \Modules\System\Admin\Expend
{
    public int $modelId;

    public function index($modelId)
    {
        $this->modelId = $modelId;
        return parent::index();
    }

    public function add($modelId)
    {
        $this->modelId = $modelId;
        return parent::add();
    }

    public function edit($modelId, $id)
    {
        $this->modelId = $modelId;
        return parent::edit($id);
    }

    public function page($modelId, $id = 0)
    {
        $this->modelId = $modelId;
        return parent::page($id);
    }

    public function save($modelId, $id = 0)
    {
        $this->modelId = $modelId;
        return parent::save($id);
    }

    public function del($modelId, $id = 0)
    {
        $this->modelId = $modelId;
        return parent::del($id);
    }

    public function recovery($modelId, $id = 0)
    {
        $this->modelId = $modelId;
        return parent::recovery($id);
    }

    public function clear($modelId, $id = 0)
    {
        $this->modelId = $modelId;
        return parent::clear($id);
    }

    public function data($modelId)
    {
        $this->modelId = $modelId;
        return parent::data();
    }

    public function status($modelId, $id = 0)
    {
        $this->modelId = $modelId;
        return parent::status($id);
    }
}
