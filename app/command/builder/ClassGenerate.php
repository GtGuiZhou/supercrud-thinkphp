<?php


namespace app\command\builder;


class ClassGenerate
{
    private $className = '';
    private $namespace = '';
    private $parentClass = '';
    private $uses = [];
    private $usesCode = '';
    private $functions = [];
    private $functionsCode = '';
    private $properties = [];
    private $propertiesCode = '';

    /**
     * @return mixed
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * @param mixed $className
     * @return ClassGenerate
     */
    public function setClassName($className): ClassGenerate
    {
        $this->className = $className;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @param mixed $namespace
     * @return ClassGenerate
     */
    public function setNamespace($namespace): ClassGenerate
    {
        $this->namespace = $namespace;
        return $this;
    }

    /**
     * @return string
     */
    public function getParentClass(): string
    {
        return $this->parentClass;
    }

    /**
     * 设置父类
     * @param $class
     * @return ClassGenerate
     */
    public function setParentClass($class): ClassGenerate
    {
        $reflect = new \ReflectionClass($class);
        $className = $reflect->getShortName();
        $this->appendUses($class);
        $this->parentClass = "extends $className";
        return $this;
    }

    /**
     * 添加引用类
     * @param $class
     * @return $this
     */
    public function appendUses($class)
    {
        $reflect = new \ReflectionClass($class);
        $use = $reflect->getName();
        $this->uses[] = $use;
        $this->usesCode = '';
        foreach ($this->uses as $use){
            $this->usesCode .= "use $use;\r\n";
        }
        return $this;
    }

    /**
     * 添加函数
     * @param FunctionGenerate $function
     * @return $this
     */
    public function appendFunction(FunctionGenerate $function)
    {
        $this->functions[] = $function;
        $this->functionsCode = '';
        foreach ($this->functions as $function){
            $this->functionsCode .= $function->builderCode() . "\r\n";
        }

        return $this;
    }


    public function appendProperty($property)
    {
        $this->properties[] = $property;
        $this->functionsCode = '';
        foreach ($this->properties as $property){
            $this->propertiesCode .= "  $property;\r\n";
        }
        return $this;
    }



    public function __construct($className,$namespace)
    {
        $this->className = $className;
        $this->namespace = $namespace;
    }


    public function builderCode()
    {

        $code = <<<EOF
<?php
namespace $this->namespace;


$this->usesCode

class $this->className $this->parentClass
{

$this->propertiesCode

$this->functionsCode                
}
EOF;

        return $code;
    }

    
}