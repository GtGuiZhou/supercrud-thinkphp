<?php


namespace app\exceptions;


use think\Exception;

class NoLoginException extends Exception
{
    protected $message = '需要登录才能进行该操作！';
}