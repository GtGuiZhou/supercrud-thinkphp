<?php
declare (strict_types = 1);

namespace app;

use app\exceptions\ControllerException;
use app\model\UserModel;
use think\App;
use think\db\Query;
use think\Model;

/**
 * 用户控制器基础类
 */
class UserController extends BaseController
{

    /**
     * 登录的用户模型
     * @var UserModel
     */
    protected $user;

    /**
     * @var Model
     */
    protected $model;


    public function __construct(App $app)
    {
        parent::__construct($app);

        // 为了方便操作将登录用户绑定到当前类
        if ($this->auth && $this->auth->isLogin()){
            $this->user = &$this->auth->user;
        }
    }


}
