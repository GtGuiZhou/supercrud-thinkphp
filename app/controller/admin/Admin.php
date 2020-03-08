<?php


namespace app\controller\admin;


use app\AdminController;
use app\model\AdminModel;
use think\db\Query;

class Admin extends AdminController
{
    protected $indexWith = ['role'];
    protected $searchField = 'username';
    protected $updatePolicy = ['root','password'];
    protected $indexHiddenField = ['password'];

    protected function indexQuery(Query $query)
    {

    }

    protected function initialize()
    {
        $this->model = new AdminModel();
        $this->insertValidate = [
            'username|用户名' => 'require|length:6,16',
            'password|密码' => 'require|length:6,16'
        ];
        $this->updateValidate = [
            'role_id|角色' => 'require'
        ];
    }


    public function updateRoot(AdminModel $admin)
    {
        $status = $this->request->put('status');
        $admin->root = $status;
        $admin->save();
    }

    public function updatePassword(AdminModel $admin)
    {
        $this->validate($this->request->put(),['newPassword|新密码' => 'require|length:6,16']);
        $password = $this->request->put('newPassword');
        $admin->password = $password;
        $admin->save();
    }

    /**
     * 登录某个管理员的账号
     * @param AdminModel $admin
     * @return \think\response\Json
     */
    public function login(AdminModel $admin)
    {
        $token = $this->auth->saveLogin($admin);
        $admin->loginRecord()->save(['ip' => $this->request->ip()]);
        return json($admin)->header([
            'set-token' => $token
        ]);
    }

}
