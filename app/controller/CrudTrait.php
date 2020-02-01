<?php


namespace app\controller;


use app\exceptions\ControllerException;
use think\db\Query;
use think\Model;

trait CrudTrait
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
    protected $insertValidate = [];

    public function index()
    {
        $page = $this->request->get('page',1);
        $size = $this->request->get('size',10);
        $search = $this->request->get('search');
        $order = $this->request->get('order');
        $list = $this->model->page($page,$size)
            ->where(function (Query $query) use ($search,$order){
                if ($search){
                    $query->whereLike($this->searchField,"%$search%");
                }
            })->select();

        return json($list);
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
        $model = $this->model->create($data);
        if(!$model->isExists()){
            throw new ControllerException('新增数据失败');
        }
        return json($model);
    }
}