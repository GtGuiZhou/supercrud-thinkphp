<?php


namespace app\exceptions;


class NoPermissionException extends \Exception
{
    protected $message = '无权访问';
}