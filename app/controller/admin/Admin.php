<?php


namespace app\controller\admin;


use app\AdminController;
use app\model\AdminModel;

class Admin extends AdminController
{
    protected $indexWith = 'role';
    protected $searchField = 'username';
    protected $updatePolicy = ['root'];


    protected function initialize()
    {
        $this->model = new AdminModel();
        $this->insertValidate = [
            'username|用户名' => 'require|length:6,16',
            'password|密码' => 'require|length:6,16'
        ];
        $this->updateValidate = [
            'password|密码' => 'require|length:6,16'
        ];
    }


    public function updateRoot($id)
    {
        $admin = $this->admin->findOrFail($id);
        $status = $this->request->put('status');
        $admin->root = $status;
        $admin->save();
    }


}
