<?php
declare (strict_types = 1);

namespace app\controller\user;

use app\exceptions\CheckException;
use app\model\UserModel;
use app\UserController;

class Auth extends UserController
{

    protected $noNeedLogin = ['login','register'];

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
        $admin = $this->model->where('username',$data['username'])
            ->findOrEmpty();
        if ($admin->isEmpty()){
            throw new CheckException('账号不存在');
        }

        if (!$admin->contrastPassword($data['password'])){
            throw new CheckException('密码错误');
        }

        $token = $this->auth->saveLogin($admin);

        return json([
            'username' => $admin->username
        ])->header([
            'set-token' => $token
        ]);

    }

    public function register()
    {
        $data = $this->request->post();
        $this->validate($data,[
           'username|用户名' => 'require|length:6,16',
           'password|密码' =>  'require|length:6,16'
        ]);
        $this->model->save($data);
        return json($this->model);

    }

    public function logout()
    {
        $this->auth->logout();
    }


    public function updatePassword()
    {
        $data = $this->validate(input(),[
            // 必须要依靠非会话信息以外的内容来修改密码,防止中间人攻击
            'old_password|老密码' => 'require|length:6,16',
            'password|密码' => 'require|length:6,16'
        ]);
        if ($this->user->contrastPassword($data['old_password'])){
            $this->user->password = $data['password'];
            $this->user->save();
            $this->auth->flush();
        } else {
            throw new CheckException('老密码错误');
        }

    }

    public function self()
    {
        return json([
            'user' => $this->user->visible(['nickname','avatar']),
            'luck_record' => $this->user->luckRecord()->withJoin('gift')->select()
        ]);
    }

}
