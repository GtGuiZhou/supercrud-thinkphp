<?php


namespace app\service;


use app\exceptions\CheckException;
use app\model\FileModel;
use GuzzleHttp\Client;
use think\facade\Cache;
use think\File;

/**
 * 文件存储服务，支持分布式存储
 * Class FileStoreService
 * @package app\service
 */
class FileStoreService
{


    /**
     * 其他存储服务器的访问地址
     * @var array
     */
    private $servers;

    public function __construct($servers = [])
    {
        $this->servers = $servers;
    }

    public function getFileUrl($md5)
    {
        // 检测缓存中有没有保存该文件的保存路径
        if ($url = Cache::get(FileModel::cacheKey($md5))) {
            return $url;
        }

        // 检测数据库中有没有保存该文件的保存路径
        if ($model = FileModel::where('md5', $md5)->find()) {
            Cache::set(FileModel::cacheKey($md5), $model['url']);
            return $model['url'];
        }
        return false;
    }

    public function readLocal($md5)
    {
        $url = $this->getFileUrl($md5);
        if (!is_file(\think\facade\Filesystem::disk('local')->path($url)))
            return false;
        return \think\facade\Filesystem::disk('local')->get($url);
    }

    public function getPath($md5)
    {
        $url = $this->getFileUrl($md5);
        return \think\facade\Filesystem::disk('local')->path($url);
    }




    public function readOtherServers($md5)
    {
        foreach ($this->servers as $server) {
            if (strpos($server, 'http') !== 0) continue;
            if ($server[-1] != '/') {
                $server .= '/';
            }
            $server .= 'api/file/' . $md5;
            try {
                $client = new Client(['verify' => false]);
                $response = $client->get($server . "api/file/local/$md5");
                // 从响应头中获取文件名称
                preg_match("@filename=\"([a-zA-Z0-9\.]+)\"@", $response->getHeaderLine('content-disposition'), $matches);
                if (count($matches) > 1) {
                    $filename = $matches[1];
                } else {
                    $filename = $md5;
                }
                return ['file' => $response->getBody()->getContents(), 'filename' => $filename];
            } catch (\Exception $e) {
                return false;
            }
        }

        return false;
    }

    public function save(File $file): string
    {
        return \think\facade\Filesystem::disk('local')->putFIle('upload', $file, 'md5');
    }
}