<?php
declare (strict_types = 1);

namespace app;

use app\middleware\auth\Auth;
use app\model\AdminModel;
use app\model\UserModel;
use think\App;
use think\exception\ValidateException;
use think\Model;
use think\Validate;

/**
 * 控制器基础类
 */
abstract class BaseController
{
    /**
     * 是否自动抛出异常
     * @var bool
     */
    protected $failException = true;

    /**
     * 登录的管理员模型
     * @var AdminModel
     */
    protected $admin;

    /**
     * 登录的用户模型
     * @var UserModel
     */
    protected $user;

    /**
     * 鉴权对象
     * @var Auth
     */
    protected $auth = null;


    protected $noNeedLogin = [];
    protected $noNeedRule = [];

    /**
     * Request实例
     * @var \think\Request
     */
    protected $request;

    /**
     * 应用实例
     * @var \think\App
     */
    protected $app;

    /**
     * 是否批量验证
     * @var bool
     */
    protected $batchValidate = false;

    /**
     * 控制器中间件
     * @var array
     */
    protected $middleware = [];


    /**
     * 当前控制器所操作的模型
     * @var Model
     */
    protected $model;


    /**
     * 自动根据get中的id获取模型数据
     * @var Model
     */
    protected $autoGetModel;

    /**
     * 构造方法
     * @access public
     * @param  App  $app  应用对象
     */
    public function __construct(App $app)
    {
        $this->app     = $app;
        $this->request = $this->app->request;
        if ($this->app->exists('auth')){
            $this->auth = $this->app->auth;
        }
        // 为了方便操作将登录用户绑定到当前类
        if ($this->auth && $this->auth->isLogin()){
            $this->admin = $this->auth->user;
            $this->user = $this->auth->user;
        }

        // 控制器初始化
        $this->initialize();
        $id = $this->request->get('id', false);
        if ($this->model && $id !== false ){
            $this->autoGetModel = $this->model->findOrFail($id);
        }
    }

    // 初始化
    protected function initialize()
    {}

    /**
     * 验证数据，验证成功返回验证后的数据
     * @access protected
     * @param  array        $data     数据
     * @param  string|array $validate 验证器名或者验证规则数组
     * @param  array        $message  提示信息
     * @param  bool         $batch    是否批量验证
     * @return array|string|true
     * @throws ValidateException
     */
    protected function validate(array $data, $validate, array $message = [], bool $batch = false)
    {
        if (is_array($validate)) {
            $v = new Validate();
            $v->rule($validate);
        } else {
            if (strpos($validate, '.')) {
                // 支持场景
                [$validate, $scene] = explode('.', $validate);
            }
            $class = false !== strpos($validate, '\\') ? $validate : $this->app->parseClass('validate', $validate);
            $v     = new $class();
            if (!empty($scene)) {
                $v->scene($scene);
            }
        }

        $v->message($message);

        // 是否批量验证
        if ($batch || $this->batchValidate) {
            $v->batch(true);
        }

        $v->failException($this->failException)->check($data);

        return  $data;
    }

}
