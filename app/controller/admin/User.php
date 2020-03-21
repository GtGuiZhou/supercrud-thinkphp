<?php


namespace app\controller\admin;


use app\AdminController;
use app\model\UserModel;

class User extends AdminController
{

    protected $searchField = 'username';
    protected $exportField = ['username','nickname','email','phone','create_time','lock'];
    protected $importField = ['username','password','nickname','email','phone'];

    protected $insertValidate = [
        'username|用户名' => 'require|length:6,16',
        'password|密码' => 'require|length:6,16',
    ];
    protected $updateValidate = [
        'username|用户名' => 'require|length:6,16',
    ];

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

    public function preview()
    {
        return [
            'total' => $this->model->count(),
            'month_total' => $this->model->whereMonth('create_time')->count(),
            'week_total' => $this->model->whereWeek('create_time')->count(),
            'day_total' => $this->model->whereDay('create_time')->count(),
        ];
    }
}