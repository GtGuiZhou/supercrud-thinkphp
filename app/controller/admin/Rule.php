<?php
declare (strict_types=1);

namespace app\controller\admin;

use app\AdminController;
use app\model\AdminRuleModel;

class Rule extends AdminController
{
    protected function initialize()
    {
        $this->model = new AdminRuleModel();
    }

    public function index()
    {
        $list = AdminRuleModel::select();
        $tree = AdminRuleModel::transformTree($list->toArray());
        return json($tree);
    }


}
