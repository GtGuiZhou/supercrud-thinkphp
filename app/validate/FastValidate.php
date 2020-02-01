<?php


namespace app\validate;


use think\Validate;

class FastValidate extends Validate
{
    /**
     * @param array $rule
     */
    public function setRule(array $rule): void
    {
        $this->rule = $rule;
    }


}