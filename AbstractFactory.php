<?php

namespace ServiceLocator;

class AbstractFactory
{
    public function createInstance($className, array $arguments, array $afterInitCallBacks)
    {
        $reflectionClass = new \ReflectionClass($className);
        $instance = $reflectionClass->newInstanceArgs($arguments);

        /**
         * @var ServiceCallBack $callBack
         */
        foreach ($afterInitCallBacks as $callBack) {
            call_user_func_array(array($instance, $callBack->methodName), $callBack->methodArgs);
        }
        return $instance;
    }
}
