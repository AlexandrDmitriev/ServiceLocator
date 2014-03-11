<?php

namespace ServiceLocator;

/**
 * Class ServiceCallBack
 * @package ServiceLocator
 * @property string $methodName
 * @property array  $methodArgs
 */
class ServiceCallBack
{
    private $methodName;
    private $methodArgs;

    public function __construct($methodName, array $methodArgs)
    {
        $this->methodName = $methodName;
        $this->methodArgs = $methodArgs;
    }

    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        }
    }
}
