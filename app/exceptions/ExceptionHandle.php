<?php


namespace app\exceptions;


use think\exception\Handle;
use think\exception\ValidateException;
use think\facade\Log;
use think\Response;
use Throwable;

class ExceptionHandle extends Handle
{
    public function render($request, Throwable $e): Response
    {
        if (!config('app.debug')){
            if ($e instanceof ValidateException) {
                return json($e->getError(), 422);
            }

            if ($e instanceof ControllerException || $e instanceof MiddlewareException
                || $e instanceof ModelException || $e instanceof NoPermissionException
            || $e instanceof ServiceException || $e instanceof NoLoginException){
                return  json(['msg' => $e->getMessage()],403);
            }
            Log::error(" [{$request->url()}] ".$e->getMessage());
        }

        // 其他错误交给系统处理
        return parent::render($request, $e);
    }

}
