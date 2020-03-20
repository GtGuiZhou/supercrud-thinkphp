<?php
declare (strict_types = 1);

namespace app;

use app\exceptions\CheckException;
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
     * @var Model
     */
    protected $model;




}
