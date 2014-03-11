<?php

namespace ServiceLocator;

/**
 * Class ServiceLocator
 * Component require some auto loader
 * 
 * @package ServiceLocator
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
    protected $services;

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

    public function locate($name)
    {
        //todo: locate
    }

    public function createNewInstance()
    {

    }
}
