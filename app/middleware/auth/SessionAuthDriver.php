<?php


namespace app\middleware\auth;


use think\facade\Session;

class SessionAuthDriver extends AuthDriver
{




    public function __construct($request, $prefix)
    {
        parent::__construct($request, $prefix);
    }

    /**
     * @inheritDoc
     */
    public function sessionUser()
    {
        return Session::get($this->prefix);
    }

    /**
     * @inheritDoc
     */
    public function saveLogin(AuthUserModelInterface $authUserModel)
    {
        Session::set($this->prefix,$authUserModel);
    }

    /**
     * @inheritDoc
     */
    public function logout()
    {
        Session::delete($this->prefix);
    }
}