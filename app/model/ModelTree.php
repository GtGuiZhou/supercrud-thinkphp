<?php


namespace app\model;


use think\Model;
use think\model\Collection;

class ModelTree
{
    private $root;
    private $relationMethodsName;

    public function __construct(Model $root,$relationMethodName)
    {
        $this->relationMethodsName = $relationMethodName;
        $this->root = $this->build($root,$relationMethodName);
    }

    private function build(Model $root,$relationMethodName)
    {

        foreach ($root->$relationMethodName as &$node){
            $node = $this->build($node,$relationMethodName);
        }
        return $root;
    }

    public function echo()
    {
        return $this->root;
    }

    /**
     * 过滤树节点
     * @param callable $callback
     * @return Collection
     */
    function filter(callable $callback){
        $result = new Collection();
        $this->filterRecursion($this->root,$result,$callback);
        return $result;
    }

    /**
     * 根据字段条件过滤数组中的元素
     * @access public
     * @param string $field    字段名
     * @param mixed  $operator 操作符
     * @param mixed  $value    数据
     * @return Collection
     */
    public function where($field,$operator,$value = null)
    {
        if (is_null($value)) {
            $value = $operator;
            $operator = '=';
        }

        return $this->filter(function ($data) use ($field, $operator, $value) {
            if (strpos($field, '.')) {
                list($field, $relation) = explode('.', $field);

                $result = $data[$field][$relation] ?? null;
            } else {
                $result = $data[$field] ?? null;
            }

            switch (strtolower($operator)) {
                case '===':
                    return $result === $value;
                case '!==':
                    return $result !== $value;
                case '!=':
                case '<>':
                    return $result != $value;
                case '>':
                    return $result > $value;
                case '>=':
                    return $result >= $value;
                case '<':
                    return $result < $value;
                case '<=':
                    return $result <= $value;
                case 'like':
                    return is_string($result) && false !== strpos($result, $value);
                case 'not like':
                    return is_string($result) && false === strpos($result, $value);
                case 'in':
                    return is_scalar($result) && in_array($result, $value, true);
                case 'not in':
                    return is_scalar($result) && !in_array($result, $value, true);
                case 'between':
                    list($min, $max) = is_string($value) ? explode(',', $value) : $value;
                    return is_scalar($result) && $result >= $min && $result <= $max;
                case 'not between':
                    list($min, $max) = is_string($value) ? explode(',', $value) : $value;
                    return is_scalar($result) && $result > $max || $result < $min;
                case '==':
                case '=':
                default:
                    return $result == $value;
            }
        });
    }


    function find($field,$value){
        $list = $this->filter(function ($model) use ($field,$value){
           return  $model[$field] == $value;
        });

        if ($list->count() > 0){
            return $list[0];
        } else{
            return null;
        }
    }

    function filterRecursion($root,&$result,callable $callback){
        $relationMethodName = $this->relationMethodsName;
        foreach ($root->$relationMethodName as &$node){
            $this->filterRecursion($node,$result,$callback);
        }
        if($callback($root)){
            $result->push($root);
        }
    }
}