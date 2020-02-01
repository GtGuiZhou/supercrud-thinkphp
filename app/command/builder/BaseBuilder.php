<?php


namespace app\command\builder;


use think\facade\Db;

class BaseBuilder
{
    protected $tableInfo = [];
    protected $table = '';
    protected $namespace;

    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
    }

    public function __construct($table)
    {
        $this->table = $table;
        $this->tableInfo = Db::execute("SELECT * FROM information_schema.COLUMNS WHERE TABLE_NAME = '{$this->table}'");
    }
}