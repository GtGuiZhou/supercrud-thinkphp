<?php
declare (strict_types = 1);

namespace app\controller;

use app\exceptions\ControllerException;
use app\model\UserModel;
use app\UserController;

class User extends UserController
{

    protected $noNeedLogin = ['login','register'];

    /**
     * @var UserModel
     */
    private $model;

    protected function initialize()
    {
        $this->model = new UserModel();
    }

    public function login()
    {
        $data = $this->validate(input(),[
            'username|用户名' => 'require|length:6,16',
            'password|密码' => 'require|length:6,16'
        ]);
        $user = $this->model->where('username',$data['username'])
            ->findOrEmpty();
        if ($user->isEmpty()){
            throw new ControllerException('账号不存在');
        }

        if (!$user->contrastPassword($data['password'])){
            throw new ControllerException('密码错误');
        }

        $token = $this->auth->login($user);
        return $token;

    }

    public function register()
    {
        $data = $this->validate(input(),[
            'username|用户名' => 'require|length:6,16',
            'password|密码' => 'require|length:6,16'
        ]);
        $user = $this->model->create([
            'username' => $data['username'],
            'password' => $data['password'],
        ]);

        return json($user);
    }

    public function updatePassword()
    {
        $data = $this->validate(input(),[
            'password|密码' => 'require|length:6,16'
        ]);
        $this->user->password = $data['password'];
        $this->user->save();
        return 'success';
    }
}
