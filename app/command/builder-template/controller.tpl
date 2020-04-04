<?php


namespace app\controller\admin;


use app\AdminController;
use app\model\${className}Model;
use think\db\Query;

class ${className} extends AdminController
{

    /**
     * @var $model ${className}Model
     */
    protected $model;

    protected function initialize()
    {
        $this->model = new ${className}Model();
        $this->insertValidate = [
${validateRule}
        ];
        $this->updateValidate = [
${validateRule}
        ];
    }


}
