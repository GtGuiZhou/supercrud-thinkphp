<?php


namespace app\exceptions;


use think\Exception;

/**
 * 需要执行的方法，没有检测到登录，抛出该异常
 * Class NoLoginException
 * @package app\exceptions
 */
class NoLoginException extends Exception
{
    protected $message = '需要登录才能进行该操作！';
}