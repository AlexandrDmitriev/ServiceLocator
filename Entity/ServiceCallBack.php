<?php

namespace ServiceLocator\Entity;

use ServiceLocator\Utility\BaseAccessor;

/**
 * Class ServiceCallBack
 * @package ServiceLocator
 * @property string $methodName
 * @property array  $methodArgs
 */
class ServiceCallBack extends BaseAccessor
{
    protected $methodName;
    protected $methodArgs;

    public function __construct($methodName, array $methodArgs)
    {
        $this->methodName = $methodName;
        $this->methodArgs = $methodArgs;
    }
}
