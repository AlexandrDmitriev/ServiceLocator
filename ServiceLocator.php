<?php

namespace ServiceLocator;

/**
 * Class ServiceLocator
 * Component require some auto loader
 * 
 * @package ServiceLocator
 * @property array $registered
 * @property AbstractFactory $abstractFactory
 */
class ServiceLocator
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
    protected $registered;


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

        $this->registered = $config;
    }

    /**
     * @param $className
     *
     * @throws Exception
     * @return Object
     */
    public function locate($className)
    {
        if (array_key_exists($className, $this->services)) {
            return $this->services[$className];
        } elseif (array_key_exists($className, $this->registered)) {
            $service = $this->createNewInstance($className);
            $this->services[$className] = $service;
            return $this->services[$className];
        } else {
            //todo: add outer relation resolver
            throw new \Exception('Invalid Argument');
        }
    }

    /**
     * @param $className
     *
     * @return Object
     */
    public function createNewInstance($className)
    {
        $arguments = empty($this->registered[$className]['arguments'])
            ? array()
            : $this->registered[$className]['arguments'];
        $locatedArguments = $this->locateArguments($arguments);
        $callbacks = $this->getCallBacks($className, 'afterInit');
        return $this->abstractFactory->createInstance($className, $locatedArguments, $callbacks);
    }

    protected function getCallBacks($className, $type)
    {
        $callbacksList = array();
        if (!empty($this->registered[$className]['callbacks'])) {
            foreach ($this->registered[$className]['callbacks'] as $callbackType => $callback) {
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

    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        }
    }
}
