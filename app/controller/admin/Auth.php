<?php


namespace app\controller\admin;


use app\AdminController;
use app\exceptions\ControllerException;
use app\model\AdminModel;
use app\model\AdminRoleMenuModel;
use app\model\AdminRoleRuleModel;
use app\service\EmailCaptchaService;
use app\service\SmsCaptchaService;
use think\exception\ValidateException;

class Auth extends AdminController
{
    protected $noNeedLogin = ['login', 'register'];
    protected $noNeedRule = ['menu', 'logout'];

    protected function initialize()
    {
        $this->model = new AdminModel();
    }

    public function index()
    {
        $this->admin['role'] = $this->admin->role;
        $this->admin['rule'] = $this->admin->role?$this->admin->role->rule:[];
        return json($this->admin);
    }

    public function login()
    {
        $data = $this->validate(input(), [
            'username|用户名' => 'require|length:6,16',
            'password|密码' => 'require|length:6,16'
        ]);
        $admin = $this->model->where('username', $data['username'])
            ->findOrEmpty();
        if ($admin->isEmpty()) {
            throw new ControllerException('账号不存在');
        }

        if (!$admin->contrastPassword($data['password'])) {
            throw new ControllerException('密码错误');
        }

        $token = $this->auth->saveLogin($admin);
        $admin->loginRecord()->save(['ip' => $this->request->ip()]);
        return json($admin)->header([
            'set-token' => $token
        ]);

    }

    public function updatePassword()
    {
        $data = $this->request->put([
            'username|用户名' => 'require|length:6,16',
            'new_password|新密码' => 'require|length:6,16'
        ]);
        $admin = AdminModel::where('username',$data['username'])->findOrFail();
        $this->policyCheck($admin);
        $admin->password = $data['new_password'];
        $admin->save();
    }

    public function updateEmail()
    {
        $data = $this->request->put([
            'email|邮箱' => 'require|email',
            'email_captcha|新的邮箱验证码' => 'require'
        ]);
        $this->policyCheck($this->admin);
        if (!EmailCaptchaService::check($data['email'],$data['email_captcha'],true)){
            throw new ControllerException('新邮箱验证码错误');
        }
        $this->admin->email = $data['email'];
        $this->admin->save();
    }

    public function updatePhone()
    {
        $data = $this->request->put([
            'phone|手机' => 'require|phone',
            'phone_captcha|新的手机验证码' => 'require'
        ]);
        $this->policyCheck($this->admin);
        if (!EmailCaptchaService::check($data['phone'],$data['phone_captcha'],true)){
            throw new ControllerException('新手机验证码错误');
        }
        $this->admin->phone = $data['phone'];
        $this->admin->save();
    }

    public function policyCheck($admin){
        $this->validate($this->request->put(), [
            'credentials_type|凭据类型' => 'required|in:password,email,phone',
            'credentials_value|凭据内容' => 'required',
        ]);
        $credentialsType = $this->request->put('credentials_type');
        $credentialsValue = $this->request->put('credentials_value');
        // 验证凭据是否正确
        switch ($credentialsType){
            case 'password': // 验证老密码是否正确
                if (!$admin->contrastPassword($credentialsValue))
                    throw new ControllerException('老密码不正确');
                break;
            case 'email': // 验证邮件验证码是否正确
                if (!$admin->email) throw new ControllerException('您还没有设定过邮箱');
                if (!EmailCaptchaService::check($admin->email,$credentialsValue,true)){
                    throw new ControllerException('邮件验证码不正确');
                }
                break;
            case 'phone':
                if (!$admin->phone) throw new ControllerException('您还没有设定过手机号码');
                if (!SmsCaptchaService::check($admin->phone,$credentialsValue,true)){
                    throw new ControllerException('手机验证码不正确');
                }
                break;
            default:
                throw new ValidateException('未知凭证类型');
        }
    }

    public function loginRecord()
    {
        return json($this->admin->loginRecord()->limit(50)->order('id', 'desc')->select());
    }

    public function logout()
    {
        $this->auth->logout();
    }


    public function menu()
    {
        $result = [];
        if ($this->admin->isRootRole()) {
            $result = AdminRoleMenuModel::select();
        } else {
            $result = $this->admin->role_id ? $this->admin->role->menu()->select() : [];
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



}