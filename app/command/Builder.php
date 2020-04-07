<?php
declare (strict_types=1);

namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\facade\Db;
use think\facade\View;
use think\helper\Str;

class Builder extends Command
{

    protected $hiddenColumn = ['create_time', 'update_time'];

    protected function configure()
    {
        // 指令配置
        $this->setName('builder')
            ->setDescription('the builder command');
    }

    protected function execute(Input $input, Output $output)
    {
        $database = \think\facade\Env::get('DATABASE.DATABASE');
        $tables = Db::query("select table_name,table_comment from information_schema.tables where table_schema='$database'");
        $templateDir = $this->app->getAppPath() . DIRECTORY_SEPARATOR . 'command' . DIRECTORY_SEPARATOR . 'builder-template' . DIRECTORY_SEPARATOR;
        $builderDir = $this->app->getRootPath() . DIRECTORY_SEPARATOR . 'z-builder' . DIRECTORY_SEPARATOR;
        $builderControllerDir = $builderDir . 'admin' . DIRECTORY_SEPARATOR;
        $builderModelDir = $builderDir . 'model' . DIRECTORY_SEPARATOR;
        $builderVuePageDir = $builderDir . 'vue-pages' . DIRECTORY_SEPARATOR;
        // 创建生成目录
        $mkDirs = [$builderDir, $builderControllerDir, $builderModelDir, $builderVuePageDir];
        foreach ($mkDirs as $dir) {
            if (!is_dir($dir)) mkdir($dir);
        }
        // 读取模板
        $tplController = file_get_contents($templateDir . 'controller.tpl');
        $tplModel = file_get_contents($templateDir . 'model.tpl');
        $tplVuePagePath = $templateDir . 'vue-page.tpl';
        $backendRouters = [];
        $frontendRouters = [];
        foreach ($tables as $table) {
            // 获取字段信息
            $tableName = $table['table_name'];
            $className = Str::studly($tableName);
            $columns = Db::query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_NAME = '{$tableName}'");
            // 生成控制器
            $generateController = $this->createController($className, $columns, $tplController);
            file_put_contents($builderControllerDir . "$className.php", $generateController);
            // 生成模型
            $generateModel = $this->createModel($className, $tableName, $tplModel);
            file_put_contents($builderModelDir . "${className}Model.php", $generateModel);
            // 保存后端路由信息
            $backendRouters[] = $this->createBackendRouter($tableName, $table['table_comment']);
            /// 保存前端路由信息
            $frontendRouters[] = $this->createFrontendRouter($tableName, $table['table_comment']);
            // 生成vue页面
            $pageDirName = strtolower(str_replace('_', '-', $tableName));
            $vuePageDir = $builderVuePageDir . $pageDirName . DIRECTORY_SEPARATOR;
            if (!is_dir($vuePageDir)) mkdir($vuePageDir);
            $generateVuePage = $this->createVuePage($tplVuePagePath, $tableName, $columns);
            file_put_contents($vuePageDir . 'Index.vue', $generateVuePage);
        }
        // 生成后端路由
        file_put_contents($builderDir . 'backend_router.php', "<?php\n" . implode("\n", $backendRouters));
        // 生成vue前端路由
        file_put_contents($builderDir . 'frontend_router.js',  implode(",\n", $frontendRouters));
    }

    private function createBackendRouter($tableName, $comment)
    {
        $comment = $comment ?: $tableName;
        $tableName = strtolower(str_replace('_', '-', $tableName));
        return <<<EOF
    // $comment
    fastCrud('$tableName', '$comment');
EOF;
    }

    private function createFrontendRouter($tableName, $comment)
    {
        $comment = $comment ?: $tableName;
        $tableName = strtolower(str_replace('_', '-', $tableName));
        return "{name:'$tableName',path:'/admin/$tableName',component: _import('container/$tableName/Index'),label: '$comment',menu: true,parent: 'root'}";
    }

    private function createModel($className, $tableName, $template)
    {
        $generateModel = str_replace('${className}', $className, $template);
        $generateModel = str_replace('${tableName}', $tableName, $generateModel);
        return $generateModel;
    }

    private function createController($className, $columns, $template)
    {
        // 构造验证规则
        $rules = [];
        foreach ($columns as $column) {
            $columnName = $column['COLUMN_KEY'];
            // 检测主键
            if (strtolower($columnName) == 'pri') continue;
            // 检测隐藏字段
            if (in_array($columnName,$this->hiddenColumn)) continue;
            $rules[] = $this->generateValidateRule($column);
        }
        $validateRule = implode(", \n", $rules);
        $generateController = str_replace('${className}', $className, $template);
        $generateController = str_replace('${validateRule}', $validateRule, $generateController);
        return $generateController;
    }

    /**
     * 构造控制器的验证对象
     * @param $column
     * @return string
     */
    private function generateValidateRule($column)
    {
        $columnName = $column['COLUMN_NAME'];
        $comment = $column['COLUMN_COMMENT'];
        $dataType = $column['DATA_TYPE'];
        $columnType = $column['COLUMN_TYPE'];
        $isNull = $column['IS_NULLABLE'];
        $maxLength = $column['CHARACTER_MAXIMUM_LENGTH'];

        $comment = $comment ?: $columnName;
        $left = "$columnName|$comment";
        $right = [];
        if ($isNull == 'NO') {
            $right[] = 'require';
        }

        $columnType = strtolower($columnType);
        if (strpos($columnType, 'unsigned') !== false) {
            $right[] = 'min:0';
        }

        $dataType = strtolower($dataType);
        if ($dataType == 'int' || $dataType == 'bigint' || $dataType == 'smallint') {
            $right[] = 'integer';
        }

        if ($dataType == 'float' || $dataType == 'double' || $dataType == 'decimal') {
            $right[] = 'number';
        }

        if ($dataType == 'enum') {
            preg_match("/$dataType\((.+?)\)/", $columnType, $matches);
            if (count($matches) == 2) {
                $range = $matches[1];
                $range = str_replace($range, "'", '');
                $range = str_replace($range, '"', '');
                if (mb_strlen($range) > 0) {
                    $right[] = 'in:' . $range;
                }
            }
        }

        if ($dataType == 'set') {
            $right = 'array';
        }

        if ($dataType == 'varchar' || $dataType == 'char') {
            if ($isNull == 'NO') {
                $right[] = "length:1,$maxLength";
            } else {
                $right[] = "length:0,$maxLength";
            }
        }

        if ($dataType == 'date' || $dataType == 'datetime' || $dataType == 'time' || $dataType == 'timestamp'){
            $right[] = 'date';
        }

        $right = implode('|', $right);
        return "            '$left' => '$right'";
    }


    private function createVuePage($templatePath, $tableName, $columns)
    {
        $router = strtolower(str_replace('_', '-', $tableName));
        // 生成前端请求接口url
        $url = "/admin/$tableName";

        $propertyList = [];
        foreach ($columns as $column) {
            // 读取子弹属性
            $columnName = $column['COLUMN_NAME'];
            $comment = $column['COLUMN_COMMENT'];
            $default = $column['COLUMN_DEFAULT'];
            $dataType = $column['DATA_TYPE'];
            $columnType = $column['COLUMN_TYPE'];
            $isNull = $column['IS_NULLABLE'];
            $maxLength = $column['CHARACTER_MAXIMUM_LENGTH'];
            $comment = $comment ?: $columnName;
            // 检测主键
            if (strtolower($column['COLUMN_KEY']) == 'pri') continue;
            // 检测隐藏字段
            if (in_array($columnName,$this->hiddenColumn)) continue;
            // 将字段属性放到数组里面，方便前端渲染
            $propertyList[$columnName] = ['default' => $default, 'comment' => $comment, 'type' => $dataType, 'length' => $maxLength, 'is_null' => $isNull, 'options' => []];
            if ($dataType == 'set' || $dataType == 'enum') {
                // 类型描述中取出可选值来
                preg_match("/$dataType\((.+?)\)/", $columnType, $matches);
                $options = [];
                if (count($matches) == 2) {
                    $match = $matches[1];
                    $match = str_replace( "'", '',$match);
                    $match = str_replace( '"', '',$match);
                    if (mb_strlen($match) > 0) {
                        $options = explode(',', $match);
                    }
                }
                $propertyList[$columnName]['options'] = $options;
            }

            if ($dataType == 'set') {
                // 默认值为空数组
                $propertyList[$columnName]['default'] = '[]';
            }
        }

        // 渲染模板
        return View::fetch($templatePath, [
            'url' => $url,
            'propertyList' => $propertyList
        ]);

    }


}
