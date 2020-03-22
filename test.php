<?php
include 'vendor/autoload.php';

$store = new \app\service\cloudstore\driver\Aliyun([
    // 存储区域
    'region' => 'oss-cn-shenzhen.aliyuncs.com',
    //协议头部，默认为http
    'schema' => 'https',
    'secret_id' => 'LTAI4FdJmZf8DdtWDrPBrpJu',
    'secret_key' => 'XWTOE9t45AHG271GFQRHvEkCAP6bCz',
    // 存储桶
    'bucket' => 'bjjl',
]);
$res = $store->store('test','test');
var_dump($res);
//
//use OSS\OssClient;
//use OSS\Core\OssException;
//
//// 阿里云主账号AccessKey拥有所有API的访问权限，风险很高。强烈建议您创建并使用RAM账号进行API访问或日常运维，请登录 https://ram.console.aliyun.com 创建RAM账号。
//$accessKeyId = "LTAI4FdJmZf8DdtWDrPBrpJu";
//$accessKeySecret = "XWTOE9t45AHG271GFQRHvEkCAP6bCz";
//// Endpoint以杭州为例，其它Region请按实际情况填写。
//$endpoint = "oss-cn-shenzhen.aliyuncs.com";
//// 存储空间名称
//$bucket= "bjjl";
//// <yourObjectName>上传文件到OSS时需要指定包含文件后缀在内的完整路径，例如abc/efg/123.jpg
//$object = "test";
//$content = "Hi, OSS.";
//
//try {
//    $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
//    $res = $ossClient->putObject($bucket, $object, $content);
//    var_dump($res);
//} catch (OssException $e) {
//    print $e->getMessage();
//}