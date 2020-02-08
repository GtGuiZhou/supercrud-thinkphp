<?php
declare (strict_types = 1);

namespace app\controller;

use app\AdminController;
use app\exceptions\ControllerException;
use app\model\AdminModel;

class Admin extends AdminController
{

    protected $noNeedLogin = ['login','register'];
    protected $noNeedAuth = ['flush'];

    protected function initialize()
    {
        $this->model = new AdminModel();
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
            throw new ControllerException('账号不存在');
        }

        if (!$admin->contrastPassword($data['password'])){
            throw new ControllerException('密码错误');
        }

        $token = $this->auth->login($admin);
        return $token;

    }

    public function register()
    {
        $data = $this->validate(input(),[
            'username|用户名' => 'require|length:6,16',
            'password|密码' => 'require|length:6,16'
        ]);
        $admin = $this->model->create([
            'username' => $data['username'],
            'password' => $data['password'],
        ]);

        return json($admin);
    }

    public function updatePassword()
    {
        $data = $this->validate(input(),[
            'password|密码' => 'require|length:6,16'
        ]);
        $this->auth->admin->password = $data['password'];
        $this->auth->admin->save();
        $this->auth->flush();
        return 'success';
    }




    public function roleTree()
    {

    }


    public function relationRole()
    {

    }

    public function releaseRole()
    {

    }


}
