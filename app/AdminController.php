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
     * @var Model
     */
    protected $model;

    /**
     * @var string 搜索字段
     */
    protected $searchField = '';
    protected $updateValidate = [];
    protected $updatePolicy = []; // 不允许更新的字段
    protected $insertValidate = [];

    protected $indexWith = '';

    protected function indexQuery(Query $query){
    }


    public function index()
    {
        $page = $this->request->get('page',1);
        $size = $this->request->get('size','all');
        $search = $this->request->get('search');
        $order = $this->request->get('order');
        $where = $this->request->get('where');
        $query = function (Query $query) use ($search,$order,$where){
            if ($search){
                $query->whereLike($this->searchField,"%$search%");
            }
            if ($where){
                $where = json_decode(urldecode($where),true);
                foreach ($where as $key => $val){
                    if ($val != '') $query->where($key,$val);
                }
            }
            $this->indexQuery($query);
        };
        $total = $this->model
            ->where($query)->count();

        if ($size == 'all') $size = $total;
        $list = $this->model
            ->page($page,$size)
            ->where($query)
            ->with($this->indexWith)
            ->select();
        return json(['data' => $list,'total' => $total]);
    }


    public function update($id)
    {

        $data = $this->validate($this->request->put(),$this->updateValidate);
        $model = $this->model->find($id);
        if (!$model){
            throw new ControllerException('更新数据不存在');
        }
        foreach ($this->updatePolicy as $field){
            unset($data[$field]);
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
        return json($model);
    }

}
