<?php


namespace app\service;


use app\exceptions\NoLoginException;
use app\model\SessionUserModel;
use think\Model;
use think\Request;

class AuthService
{
    /**
     * 当前登录用户所用的模型
     * @var Model
     */
    public $userModel;

    /**
     * 当前登录用户
     * @var Model
     */
    public $user;

    /**
     * 登录状态
     * @var bool
     */
    protected $logged = false;


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

    /**
     * 不需要登录的方法
     * @var array
     */
    protected $noNeedLogin = [];

    /**
     * 不需要规则鉴权的方法
     * @var array
     */
    protected $noNeedRule = [];

    /**
     * @var object|\think\App
     */
    protected $app;

    private function getSessionKey()
    {
        // 根据表名获取用户id
        return $this->userModel->getTable() . '_id';
    }

    public function __construct(SessionUserModel $userModel)
    {
        $this->app = app();
        $this->userModel = $userModel;
        $data = $this->app->session->get($this->getSessionKey());
        $user = $userModel->find($data['user_id']);
        if ($user) {
            if ($user->getUpdateTime() != $data['update_time']){
                throw new NoLoginException('个人信息已被更新,请重新登录');
            }
            $this->user = $user;
            $this->logged = true;
        }
    }


    /**
     * 保存登录状态,返回会话id
     * @param SessionUserModel $user
     * @return mixed
     */
    public function saveLogin(SessionUserModel $user)
    {
        $this->app->session->set($this->getSessionKey(), [
            'user_id' => $user[$user->getPk()],
            'update_time' => $user->getUpdateTime()
        ]);
        $this->logged = true;
        return $this->app->session->getId();
    }

    /**
     * 从系统中注销用户登录状态
     */
    public function logout()
    {
        return $this->app->session->delete($this->getSessionKey());
    }

    public function isLogin()
    {
        return $this->logged;
    }
}