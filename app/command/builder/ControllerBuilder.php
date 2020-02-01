<?php


namespace app\command\builder;


use app\UserController;

class ControllerBuilder extends BaseBuilder
{
    private $controllerName = '';
    private $bindModel;

    public function __construct($table)
    {
        parent::__construct($table);

        $this->namespace = "app\\controller";
        $this->controllerName = ucfirst(convertUnderline($this->table));
    }

    public function setControllerName($name)
    {
        $this->controllerName = $name;
        return $this;
    }

    public function setBindModel($class)
    {
        $this->bindModel = $class;
        return $this;
    }


    public function builderCode()
    {
        $classGenerate = new ClassGenerate($this->controllerName,$this->namespace);
        return $classGenerate->setParentClass(UserController::class)
            ->builderCode();
    }
}