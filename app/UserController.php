<?php
declare (strict_types = 1);

namespace app;

use app\service\UserAuth;
use think\App;

/**
 * 用户控制器基础类
 */
class UserController extends BaseController
{

    /**
     * @var UserAuth
     */
    protected $auth;

    /**
     * 当前登录用户
     * @var model\UserModel
     */
    protected $user;


    public function __construct(App $app)
    {
        parent::__construct($app);
        $token = $this->request->header('Authentication','');
        $this->auth = new UserAuth($token,$this->noNeedLogin);
        if ($this->auth->isLogin()){
            $this->user = $this->auth->user;
        }
        $this->auth->validateAction($this->request->action());
    }
}
