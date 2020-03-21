<?php


namespace app\exceptions;


use think\exception\Handle;
use think\exception\ValidateException;
use think\facade\Env;
use think\facade\Log;
use think\Response;
use Throwable;

class ExceptionHandle extends Handle
{
    public function render($request, Throwable $e): Response
    {
        // 由于tp默认是关闭错误提示的，但是如果当前文件的代码有问题的话，是无法打印出错误的，当出现没有任何内容的500错误时，路由打开下面两行代码
        // ini_set("display_errors", "On");//打开错误提示
        // ini_set("error_reporting",E_ALL);//显示所有错误

//        if ($e instanceof ValidateException) {
//            return json($e->getError(), 422);
//        }

        if ($e instanceof CheckException){
            return  json(['message' => $e->getMessage()],400);
        }
        if ($e instanceof NoLoginException){
            return  json(['message' => $e->getMessage()],401);
        }

        Log::error(" [{$request->url()}] ".$e->getMessage());

        if (!Env::get('APP_DEBUG')){
            // 生产模式,不展示错误详细信息
            return json(['message' => '服务器内部错误'],500);
        } else {
            // 开发模式,展示详细信息便于调试
            return Response::create($this->renderExceptionContent($e))->code(500);
        }
    }

}
