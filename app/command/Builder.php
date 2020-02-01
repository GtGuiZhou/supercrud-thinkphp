<?php
declare (strict_types = 1);

namespace app\command;

use app\command\builder\ControllerBuilder;
use app\command\builder\ModelBuilder;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;

class Builder extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('builder')
            ->addArgument('tableName')
            ->addOption('')
            ->setDescription('the builder command');        
    }

    protected function execute(Input $input, Output $output)
    {

        $tableName = $input->getArgument('tableName');
    	// 指令输出
        $controllerBuilder = new ControllerBuilder($tableName);
        $modelBuilder = new ModelBuilder($tableName);
        file_put_contents('controller.php',$controllerBuilder->builderCode());
        file_put_contents('model.php',$modelBuilder->builderCode());
        $output->writeln($tableName);
    }


}
