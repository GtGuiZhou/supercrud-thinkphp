<?php


namespace app\middleware\auth;


interface AuthUserModelInterface
{
    /**
     * 检测用户是否含有规则
     * 可以在这个方法中通过请求对象的信息，去对比数据库的存储信息，看看有没有这条规则
     * @param string $rule 规则
     * @return bool
     */
    public function haveRule($rule);

    /**
     * 检测是不是root用户
     * @return mixed
     */
    public function isRoot();


    /**
     * 检测用户是否已更新
     * @return mixed
     */
    public function isUpdate();


}