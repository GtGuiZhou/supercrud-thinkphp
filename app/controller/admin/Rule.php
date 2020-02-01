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
}
