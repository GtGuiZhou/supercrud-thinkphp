<?php


namespace app\command\builder;


use app\UserController;
use think\Model;

class ModelBuilder extends BaseBuilder
{
    private $modelName = '';
    protected $namespace = '';

    public function __construct($table)
    {
        parent::__construct($table);

        $this->namespace = "app\\model";
        $this->modelName = ucfirst(convertUnderline($this->table));
    }

    public function setModelName($name)
    {
        $this->modelName = $name;
        return $this;
    }

    public function builderCode()
    {
        $classGenerate = new ClassGenerate($this->modelName,$this->namespace);
        return $classGenerate->setParentClass(Model::class)
            ->appendProperty("protected \$table = '{$this->table}'")
            ->builderCode();
    }
}