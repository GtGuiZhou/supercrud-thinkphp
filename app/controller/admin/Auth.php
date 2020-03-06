<?php


namespace app\controller\admin;


use app\AdminController;
use app\exceptions\ControllerException;
use app\model\AdminRoleMenuModel;
use app\model\AdminModel;
use app\model\AdminRoleRuleModel;
use think\Db;
use think\Model;

class Auth extends AdminController
{
    protected $noNeedLogin = ['login','register'];
    protected $noNeedRule = ['menu','logout'];

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
            ->with(['role' => ['rule']])
            ->findOrEmpty();
        if ($admin->isEmpty()){
            throw new ControllerException('账号不存在');
        }

        if (!$admin->contrastPassword($data['password'])){
            throw new ControllerException('密码错误');
        }

        $token = $this->auth->saveLogin($admin);
        $admin->loginRecord()->save(['ip' => $this->request->ip()]);
        return json($admin)->header([
            'set-token' => $token
        ]);

    }

    public function loginRecord()
    {
         return json($this->admin->loginRecord()->limit(50)->order('id','desc')->select());
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
        if ($this->admin->contrastPassword($data['old_password'])){
            $this->admin->password = $data['password'];
            $this->admin->save();
            $this->auth->flush();
        } else {
            throw new ControllerException('老密码错误');
        }

    }


    public function menu()
    {
        $result = [];
        if ($this->admin->isRootRole()){
            $result =  AdminRoleMenuModel::select();
        } else {
            $result = $this->admin->role_id?$this->admin->role->menu()->select():[];
        }
        return json($result);
    }

    public function rulesTree()
    {
        $list = $this->admin->role->rules->toArray();
        $result = AdminRoleRuleModel::transformTree($list);
        return json($result);

    }

    public function rulesList()
    {
        $list = $this->admin->role->rules->toArray();
        return json($list);
    }


    public function index()
    {
        return [
            'admin' => $this->admin
        ];
    }

}