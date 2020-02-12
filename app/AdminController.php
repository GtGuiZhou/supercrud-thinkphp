<?php
declare (strict_types = 1);

namespace app;

use app\controller\CrudTrait;
use app\exceptions\ControllerException;
use app\model\AdminModel;
use think\App;
use think\db\Query;
use think\Model;

/**
 * 管理员控制器基础类
 */
class AdminController extends BaseController
{

    /**
     * 登录的管理员模型
     * @var AdminModel
     */
    protected $admin;

    /**
     * @var Model
     */
    protected $model;

    /**
     * @var string 搜索字段
     */
    protected $searchField = '';
    protected $updateValidate = [];
    protected $insertValidate = [];

    public function __construct(App $app)
    {
        parent::__construct($app);

        // 为了方便操作将登录用户绑定到当前类
        if ($this->auth && $this->auth->isLogin()){
            $this->admin = &$this->auth->user;
        }
    }

    public function index()
    {
        $page = $this->request->get('page',1);
        $size = $this->request->get('size',10);
        $search = $this->request->get('search');
        $order = $this->request->get('order');
        $query = function (Query $query) use ($search,$order){
            if ($search){
                $query->whereLike($this->searchField,"%$search%");
            }
        };
        $list = $this->model->page($page,$size)
            ->where($query)->select();
        $total = $this->model->where($query)->count();
        return json(['data' => $list,'total' => $total]);
    }

    public function update($id)
    {

        $data = $this->validate($this->request->put(),$this->updateValidate);
        $model = $this->model->find($id);
        if (!$model){
            throw new ControllerException('更新数据不存在');
        }
        $model->save($data);
        return json($model);
    }

    public function delete($id)
    {
        $model = $this->model->find($id);
        if (!$model){
            throw new ControllerException('删除数据不存在');
        }
        $model->delete();

    }

    public function insert()
    {
        $data = $this->validate($this->request->post(),$this->insertValidate);
        $model = $this->model->save($data);
        if(!$model->isExists()){
            throw new ControllerException('新增数据失败');
        }
        return json($model);
    }

}
