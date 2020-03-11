<?php


namespace app\controller\admin;


use app\AdminController;
use app\model\UserModel;

class User extends AdminController
{

    protected $searchField = 'username';
    protected function initialize()
    {
        $this->model = new UserModel();
    }

    public function updatePassword(UserModel $user)
    {
        $this->validate($this->request->put(),[
            'newPassword|新密码' => 'require|length:6,16'
        ]);

        $user->password = $this->request->put('newPassword');
        $user->save();
    }

    public function updateLock(UserModel $user)
    {
        $status = $this->request->put('status');
        $user->lock = $status;
        $user->save();
    }
}