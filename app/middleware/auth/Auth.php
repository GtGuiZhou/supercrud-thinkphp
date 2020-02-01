<?php


namespace app\middleware\auth;


use app\exceptions\MiddlewareException;
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
     * 会话维持驱动
     * @var AuthDriver
     */
    protected $driver;

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

    public function __construct($driver, $prefix = '')
    {
        $this->request = app('request');
        $this->prefix = $prefix;
        $this->driver = new $driver($this->request, $this->prefix);
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
        $user = $this->driver->sessionUser();
        if ($user instanceof AuthUserModelInterface) {
            $this->user = $user;
            $this->logged = true;
        }
    }

    /**
     * 保存登录状态
     * @param AuthUserModelInterface $user
     * @return mixed
     */
    public function saveLogin(AuthUserModelInterface $user)
    {
        $res = $this->driver->saveLogin($user);
        $this->user = $user;
        $this->logged = true;
        return $res;
    }

    /**
     * 从系统中注销用户登录状态
     */
    public function logout()
    {
        return $this->driver->logout();
    }

    public function isLogin()
    {
        return $this->logged;
    }


    /**
     * 登录检测
     * @throws MiddlewareException
     */
    public function loginPolicy()
    {
        if (in_array($this->request->action(),$this->noNeedLogin)) return;
        if (!$this->isLogin())
            throw new MiddlewareException('请先登录');
    }

    /**
     * 规则权限检测
     * @throws MiddlewareException
     */
    public function rulePolicy()
    {
        if (in_array($this->request->action(),$this->noNeedLogin)) return;
        if (in_array($this->request->action(),$this->noNeedRule)) return;
        if ($this->isLogin() && !$this->user->isRoot() ) {
            $rule = $this->request->controller() . '/' . $this->request->action();
            if (!$this->user->haveRule(strtolower($rule))) {
                throw new MiddlewareException('无权访问');
            }
        }
    }


}