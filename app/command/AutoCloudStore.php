<?php
declare (strict_types=1);

namespace app\command;

use app\service\cloudstore\CloudStore;
use app\model\FileModel;
use think\console\Command;
use think\console\Input;
use think\console\Output;

class AutoCloudStore extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('acs')
            ->addArgument('driverName', null, '', null)
            ->addArgument('sleep', null, '', 1)
            ->setDescription('自动将文件发送到云存储');
    }

    protected function execute(Input $input, Output $output)
    {
        // 指令输出
        $driverName = $input->getArgument('driverName');
        $sleep = $input->getArgument('sleep');
        $driverName = $driverName ? $driverName : config('cloudstore.default');
        $driverConfig = config("cloudstore.driver.$driverName", null);
        if (!$driverConfig) {
            $output->writeln("$driverName: 不存在");
            return;
        }
        $cloudStore = new CloudStore($driverConfig);
//        while (true) {z
        $syncFiles = FileModel::where('driver', 'local')->limit(100)->select();
        foreach ($syncFiles as $file) {
            $md5 = $file['md5'];
            try {
                $path = config('filesystem.disks.local.root') . DIRECTORY_SEPARATOR . $file['local_url'];
                $url = $cloudStore->storeByPath($path);
                // 更新数据库记录
                $file['driver'] = $driverName;
                $file['url'] = $url;
                $file->save();
                $log = date('Y-m-d H:i:s') . " - success,同步文件[$md5],返回[$url],驱动设备[$driverName]";
            } catch (\Exception $e) {
                $msg = $e->getMessage();
                $line = $e->getLine();
                $file = $e->getFile();
                $log = date('Y-m-d H:i:s') . " - fail,同步文件[$md5],异常信息[$msg],驱动设备[$driverName],file[$file],line[$line]";
            } finally {
                $output->writeln($log);
                file_put_contents('cloudstore.log', $log, FILE_APPEND);
            }
        }
        sleep($sleep);
//        }
    }
}
