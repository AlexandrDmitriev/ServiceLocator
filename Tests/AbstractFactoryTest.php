<?php

namespace ServiceLocator\Tests;

use ServiceLocator\AbstractFactory;
use ServiceLocator\Entity\ServiceCallBack;

class AbstractFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AbstractFactory
     */
    protected $factory;

    public function setUp()
    {
        $this->factory = new AbstractFactory();
    }

    public function testCreateInstanceShouldCreateClassInstance()
    {
        $actual = $this->factory->createInstance('\stdClass', array(), array());
        $this->assertEquals(new \stdClass(), $actual);
    }

    public function testCreateInstanceShouldCallCallbackAfterInit()
    {
        $expectedMethod = 'init';
        $expectedParam = 42;
        $expectedArguments = array($expectedParam);
        $expected = new AbstractFactoryStubClass();
        $expected->params = $expectedParam;
        $expectedCallback = new ServiceCallBack($expectedMethod, $expectedArguments);
        $actual = $this->factory->createInstance('\ServiceLocator\Tests\AbstractFactoryStubClass', array(), array($expectedCallback));
        $this->assertEquals($expected, $actual);
    }
}
