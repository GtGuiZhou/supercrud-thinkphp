<?php


namespace app\middleware\auth;


use app\exceptions\NoLoginException;
use app\exceptions\NoPermissionException;
use think\Request;

class Auth
{
    /**
     * 当前登录用户
     * @var AuthUserModelInterface
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

    public function __construct($prefix = '')
    {
        $this->app = app();
        $this->request = $this->app->request;
        $this->prefix = $prefix;
        $this->autoLogin();

        // 鉴权
        $controller = 'app\\controller\\' . str_replace('.', '\\', $this->request->controller());
        $reflect = new \ReflectionClass($controller);
        $defaultProperties = $reflect->getDefaultProperties();
        $this->noNeedLogin = empty($defaultProperties['noNeedLogin']) ? [] : $defaultProperties['noNeedLogin'];
        $this->noNeedRule = empty($defaultProperties['noNeedRule']) ? [] : $defaultProperties['noNeedRule'];
        $this->loginPolicy();
        $this->rulePolicy();

    }

    /**
     * 自动检测登录
     */
    public function autoLogin()
    {
        $user = $this->app->session->get($this->prefix);
        if ($user instanceof AuthUserModelInterface) {
            if($user->isUpdate()){
                throw new NoLoginException('信息已被更新,请重新登录');
            }
            $this->user = $user;
            $this->logged = true;
        }
    }

    /**
     * 保存登录状态,返回会话id
     * @param AuthUserModelInterface $user
     * @return mixed
     */
    public function saveLogin(AuthUserModelInterface $user)
    {
        $this->app->session->set($this->prefix,$user);
        $this->user = $user;
        $this->logged = true;
        return $this->app->session->getId();
    }

    /**
     * 从系统中注销用户登录状态
     */
    public function logout()
    {
        return $this->app->session->delete($this->prefix);
    }

    public function isLogin()
    {
        return $this->logged;
    }

    /**
     * 登录检测
     * @throws NoLoginException
     */
    public function loginPolicy()
    {
        if (in_array($this->request->action(),$this->noNeedLogin)) return;
        if (!$this->isLogin())
            throw new NoLoginException('请先登录');
    }


    /**
     * 将当前会话用户重新保存到会话记录中
     */
    public function flush()
    {
        $this->app->session->set($this->prefix,$this->user);
    }

    /**
     * 规则权限检测
     * @throws NoPermissionException
     */
    public function rulePolicy()
    {
        if (in_array($this->request->action(),$this->noNeedLogin)) return;
        if (in_array($this->request->action(),$this->noNeedRule)) return;
        if ($this->isLogin() && !$this->user->isRoot() ) {
            $rule = $this->request->controller() . '/' . $this->request->action();
            if (!$this->user->haveRule(strtolower($rule))) {
                throw new NoPermissionException('无权访问');
            }
        }
    }




}