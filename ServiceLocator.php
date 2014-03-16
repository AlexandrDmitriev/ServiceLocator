<?php

namespace ServiceLocator;

use CoreInterfaces\IServiceLocator;
use ServiceLocator\Entity\ServiceCallBack;
use ServiceLocator\Exceptions\ServiceLocatorException;
use ServiceLocator\Utility\AdditionalParamsBehavior;
use ServiceLocator\Utility\BaseAccessor;

/**
 * Class ServiceLocator
 * Component require some auto loader
 * 
 * @package ServiceLocator
 * @property array $config
 * @property AbstractFactory $abstractFactory
 */
class ServiceLocator extends BaseAccessor implements IServiceLocator
{
    /**
     * @var AbstractFactory
     */
    protected $abstractFactory;

    /**
     * Array of service instances
     * @var array
     */
    protected $services = array();

    /**
     * Array of services available to load
     * @var array
     */
    protected $config;

    protected $additionalParams;

    /**
     * @param array           $config
     * @param AbstractFactory $abstractFactory
     */
    public function __construct(array $config, AbstractFactory $abstractFactory = null)
    {
        if ($abstractFactory === null) {
            $abstractFactory = new AbstractFactory();
        }

        $this->abstractFactory = $abstractFactory;

        $this->config = $config;
    }

    /**
     * @param $className
     *
     * @throws \Exception
     * @return Object
     */
    public function locate($className)
    {
        if (array_key_exists($className, $this->services)) {
            return $this->services[$className];
        } elseif (array_key_exists($className, $this->config)) {
            $service = $this->createNewInstance($className);
            $this->services[$className] = $service;
            return $this->services[$className];
        } elseif (array_key_exists($className, $this->additionalParams)) {
            return $this->additionalParams[$className];
        } else {
            throw new ServiceLocatorException("Can't resolve dependency with alias: {$className}");
        }
    }

    /**
     * @param $className
     *
     * @throws ServiceLocatorException
     * @return Object
     */
    public function createNewInstance($className)
    {
        if (!array_key_exists($className, $this->config)) {
            throw new ServiceLocatorException("Class {$className} not found in configuration");
        }

        $arguments = empty($this->config[$className]['arguments'])
            ? array()
            : $this->config[$className]['arguments'];
        $locatedArguments = $this->locateArguments($arguments);
        $callbacks = $this->getCallBacks($className, 'afterInit');
        return $this->abstractFactory->createInstance($className, $locatedArguments, $callbacks);
    }

    protected function getCallBacks($className, $type)
    {
        $callbacksList = array();
        if (!empty($this->config[$className]['callbacks'])) {
            foreach ($this->config[$className]['callbacks'] as $callbackType => $callback) {
                if ($callbackType != $type) {
                    continue;
                }
                $methodName = key($callback);
                $locatedArguments = $this->locateArguments($callback[$methodName]);
                $callbacksList[] = new ServiceCallBack($methodName, $locatedArguments);
            }
        }
        return $callbacksList;
    }

    protected function locateArguments(array $arguments)
    {
        $locatedArguments = array();

        foreach ($arguments as $argument) {
            $locatedArguments[] = $this->locate($argument);
        }

        return $locatedArguments;
    }

    public function addAdditionalParam($alias, $value, $behavior = AdditionalParamsBehavior::FIRE_EXCEPTION)
    {
        $paramExist = array_key_exists($alias, $this->additionalParams);
        if (!$paramExist || $behavior == AdditionalParamsBehavior::REPLACE) {
            $this->additionalParams[$alias] = $value;
        } elseif ($behavior == AdditionalParamsBehavior::FIRE_EXCEPTION) {
            throw new ServiceLocatorException("Parameter: {$alias} already exist");
        }
    }
}
