<?php
declare (strict_types = 1);

namespace app\command;

use app\command\builder\ControllerBuilder;
use app\command\builder\ModelBuilder;
use http\Env;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\facade\Db;
use think\helper\Str;

class Builder extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('builder')
            ->setDescription('the builder command');        
    }

    protected function execute(Input $input, Output $output)
    {
        $database = \think\facade\Env::get('DATABASE.DATABASE');
        $tables = Db::query("select table_name from information_schema.tables where table_schema='$database'");
        $templateDir = $this->app->getAppPath().DIRECTORY_SEPARATOR.'command'.DIRECTORY_SEPARATOR.'builder-template'.DIRECTORY_SEPARATOR;
        $builderDir = $this->app->getRootPath().DIRECTORY_SEPARATOR.'z-builder' .DIRECTORY_SEPARATOR;
        $builderControllerDir = $builderDir.'admin'.DIRECTORY_SEPARATOR;
        $builderModelDir = $builderDir.'model'.DIRECTORY_SEPARATOR;
        // 创建生成目录
        $mkDirs = [$builderDir,$builderControllerDir,$builderModelDir];
        foreach ($mkDirs as $dir){
            if (!is_dir($dir)) mkdir($dir);
        }
        // 读取模板
        $tplController = file_get_contents($templateDir.'controller.tpl');
        $tplModel = file_get_contents($templateDir.'model.tpl');
        foreach ($tables as $table){
            // 获取字段信息
            $table = $table['table_name'];
            $className = Str::studly($table);
            $columns = Db::query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_NAME = '{$table}'");
            // 生成控制器
            $generateController = $this->createController($className,$columns,$tplController);
            file_put_contents($builderControllerDir."$className.php",$generateController);
            // 生成模型
            $generateModel = $this->createModel($className,$table,$tplModel);
            file_put_contents($builderModelDir."${className}Model.php",$generateModel);
        }
    }

    private function createModel($className,$tableName,$template){
        $generateModel = str_replace('${className}',$className,$template);
        $generateModel = str_replace('${tableName}',$tableName,$generateModel);
        return $generateModel;
    }

    private function createController($className,$columns,$template){
        // 构造验证规则
        $rules = [];
        foreach ($columns as $column){
            if (strtolower($column['COLUMN_KEY']) != 'pri'){
                $rules[] = $this->generateValidateRule($column);
            }
        }
        $validateRule = implode(", \n",$rules);
        $generateController = str_replace('${className}',$className,$template);
        $generateController = str_replace('${validateRule}',$validateRule,$generateController);
        return $generateController;
    }

    private function generateValidateRule($column){
        $columnName = $column['COLUMN_NAME'];
        $comment = $column['COLUMN_COMMENT'];
        $dataType = $column['DATA_TYPE'];
        $columnType = $column['COLUMN_TYPE'];
        $isNull = $column['IS_NULLABLE'];
        $maxLength = $column['CHARACTER_MAXIMUM_LENGTH'];


        $comment = $comment?:$columnName;
        $left = "$columnName|$comment";
        $right = [];
        if ($isNull == 'NO'){
            $right[] = 'require';
        }

        $columnType = strtolower($columnType);
        if (strpos($columnType,'unsigned') !== false){
            $right[] = 'min:0';
        }

        $dataType = strtolower($dataType);
        if ($dataType == 'int'){
            $right[] = 'integer';
        }

        if ($dataType == 'float' || $dataType == 'double' || $dataType == 'decimal'){
            $right[] = 'number';
        }

        if ($dataType == 'enum' || $dataType == 'set'){
            preg_match("/$dataType\((.+?)\)/","enum('unpaid','paid','paid_fail')",$matches);
            if ($matches == 1){
                $range = $matches[1];
                $range = str_replace($range,"'",'');
                $range = str_replace($range,'"','');
                if (mb_strlen($range) > 0){
                    $right[] = 'in:'.$range;
                }
            }
        }

        if ($dataType == 'varchar' || $dataType == 'char'){
            if ($isNull == 'NO'){
                $right[] = "length:1,$maxLength";
            } else {
                $right[] = "length:0,$maxLength";
            }
        }

        $right = implode('|',$right);
        return "            '$left' => '$right'";
    }


}
