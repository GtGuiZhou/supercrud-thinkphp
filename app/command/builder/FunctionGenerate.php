<?php


namespace app\command\builder;


class FunctionGenerate
{
    private $funcName;
    private $code;
    private $accessType;
    private $static;
    private $params;
    private $funcComment;
    private $paramsComment;


    public function __construct($funcName)
    {
        $this->funcName = $funcName;
    }

    /**
     * @return mixed
     */
    public function getFuncName()
    {
        return $this->funcName;
    }

    /**
     * @param mixed $funcName
     */
    public function setFuncName($funcName): void
    {
        $this->funcName = $funcName;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code): void
    {
        $this->code = $code;
    }

    /**
     * @return mixed
     */
    public function getAccessType()
    {
        return $this->accessType;
    }

    /**
     * @param mixed $accessType
     * @return FunctionGenerate
     */
    public function setAccessType($accessType)
    {
        if (!in_array($accessType,['public','protected','private'])){
            throw new \Exception('未知方法访问类型');
        }
        $this->accessType = $accessType;
        return $this;
    }

    /**
     * @return bool
     */
    public function getStatic()
    {
        if ($this->static == 'static')
            return true;
        else
            return false;
    }

    /**
     * @param bool $static
     * @return FunctionGenerate
     */
    public function setStatic(bool $static)
    {
        if ($static)
            $this->static = 'static';
        else
            $this->static = '';

        return $this;
    }

    /**
     * @return mixed
     */
    public function getParams()
    {
        return explode(',',$this->params);
    }

    /**
     * @param array $params
     * @return FunctionGenerate
     */
    public function setParams(array $params)
    {
        $this->params = implode(',',$params);
        return $this;
    }

    /**
     * 设置函数注释
     * @param $comment
     * @return FunctionGenerate
     */
    public function setFuncComment($comment)
    {
        $this->funcComment = "* $comment";
        return $this;
    }

    /**
     * 设置参数注释
     * @param array $comments
     * @return FunctionGenerate
     */
    public function setParamsComment(array $comments)
    {
        $comments = implode("\r\n",$comments);
        foreach ($comments as &$comment){
            $comment = "* @param $comment";
        }
        $this->paramsComment = $comments;
        return $this;
    }



    public function builderCode()
    {
        $comment = '';
        if ($this->funcComment || $this->paramsComment){
            $comment = <<<EOF
/**
    $this->funcComment   
    $this->paramsComment   
 **/
EOF;
        }

        $code =<<<EOF
$comment
$this->accessType $this->static function $this->funcName($this->params){
    $this->code
}         
EOF;
        return $code;
    }
}