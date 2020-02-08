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
        $user = Session::get($this->prefix);
        $this->user = $user;
        return $user;
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


    /**
     * @inheritDoc
     */
    public function flush(AuthUserModelInterface $user)
    {
        Session::set($this->prefix,$user);
    }
}