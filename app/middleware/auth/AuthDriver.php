<?php


namespace app\middleware\auth;


use think\Request;

abstract class AuthDriver
{
    /**
     * 用于区分不同模块用户的前缀
     * @var string
     */
    protected $prefix = '';


    /**
     * 请求对象
     * @var Request
     */
    protected $request;

    public function __construct($request,$prefix)
    {
        $this->request = $request;
        $this->prefix = $prefix;
    }

    /**
     * 用户已登录返回已登录用户，否则返回空
     * @return AuthUserModelInterface|null
     */
    abstract public function sessionUser();

    /**
     * 保存登录状态
     * @param AuthUserModelInterface $authUserModel
     * @return mixed
     */
    abstract public function saveLogin(AuthUserModelInterface $authUserModel);

    /**
     * 用户从系统注销
     * @return mixed
     */
    abstract public function logout();

    /**
     * 刷新用户信息
     * @param AuthUserModelInterface $user
     * @return mixed
     */
    abstract public function flush(AuthUserModelInterface $user);
}