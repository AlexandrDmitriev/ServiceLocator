<?php

namespace ServiceLocator\Tests;

use ServiceLocator\ServiceLocator;

class ServiceLocatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ServiceLocator
     */
    private $serviceLocator;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $factory;

    public function setUp()
    {
        $this->factory = $this->getMock('\ServiceLocator\AbstractFactory');
    }

    public function testConstructorMustSetServiceConfig()
    {
        $expected = array(
            'serviceAlias' => array(
                'arguments' => array('serviceAlias1', 'serviceAlias1'),
                'callbacks' => array('afterInit' => array('initMethod' => array('Alias1')))
            )
        );
        $this->serviceLocator = new ServiceLocator($expected);

        $this->assertEquals($expected, $this->serviceLocator->registered);
    }

    public function testConstructorCreateDefaultComponentFactoryIfItIsNotInArgumentsList()
    {
        $this->serviceLocator = new ServiceLocator(array());

        $this->assertInstanceOf('\ServiceLocator\AbstractFactory', $this->serviceLocator->abstractFactory);
    }

    /**
     * @expectedException \Exception
     */
    public function testLocateShouldThrowAnErrorIfClassNotRegistered()
    {
        $testClassName = 'serviceAlias';
        $config = array(
            $testClassName => array(
                'arguments' => array(),
                'callbacks' => array('afterInit' => array('initMethod' => array('init' => array())))
            )
        );
        $this->initServiceLocator($config, $this->factory);
        $this->serviceLocator->locate('Another');
    }

    public function testLocateShouldCreateNewInstanceIfItAlreadyExistInConfig()
    {
        $className = 'serviceAlias';
        $config = array(
            $className => array(
                'arguments' => array(),
                'callbacks' => array('afterInit' => array('init' => array()))
            )
        );
        $expected = new \StdClass();
        $this->factory->expects($this->once())->method('createInstance')->will($this->returnValue($expected));
        $this->initServiceLocator($config, $this->factory);
        $actual = $this->serviceLocator->locate($className);
        $this->assertEquals($expected, $actual);
    }

    public function testLocateShouldNotCreateNewInstanceIfItAlreadyExist()
    {
        $testClassName = 'serviceAlias';
        $config = array(
            $testClassName => array(
                'arguments' => array(),
                'callbacks' => array('afterInit' => array('initMethod' => array()))
            )
        );
        $expected = new \StdClass();
        $this->factory->expects($this->once())->method('createInstance')->will($this->returnValue($expected));
        $this->initServiceLocator($config, $this->factory);
        $this->serviceLocator->locate($testClassName);
        $actual = $this->serviceLocator->locate($testClassName);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @expectedException \ServiceLocator\Exceptions\ServiceLocatorException
     */
    public function testCreteNewInstanceShouldThrowAnErrorIfClassNotRegistered()
    {
        $testClassName = 'serviceAlias';
        $config = array(
            $testClassName => array(
                'arguments' => array(),
                'callbacks' => array('afterInit' => array('initMethod' => array('init' => array())))
            )
        );
        $this->initServiceLocator($config, $this->factory);
        $this->serviceLocator->createNewInstance('Another');
    }

    public function testCreteNewInstanceShouldNotCallFactoryMethod()
    {
        $testClassName = 'serviceAlias';
        $config = array(
            $testClassName => array(
                'arguments' => array(),
                'callbacks' => array('afterInit' => array('initMethod' => array('init' => array())))
            )
        );
        $this->factory->expects($this->never())->method('createInstance');
        $this->initServiceLocator($config, $this->factory);
        try {
            $this->serviceLocator->createNewInstance('Another');
        } catch (\Exception $e) {
            //previous test fill this case
        }
    }

    public function testCreteNewInstanceShouldCreateNewInstance()
    {
        $testClassName = 'serviceAlias';
        $config = array(
            $testClassName => array(
                'arguments' => array(),
                'callbacks' => array('afterInit' => array('initMethod' => array()))
            )
        );
        $expected = new \StdClass();
        $this->factory->expects($this->once())->method('createInstance')->will($this->returnValue($expected));
        $this->initServiceLocator($config, $this->factory);
        $actual = $this->serviceLocator->createNewInstance($testClassName);
        $this->assertEquals($expected, $actual);
    }

    public function testCreteNewInstanceShouldCreateNewInstanceInEveryCall()
    {
        $testClassName = 'serviceAlias';
        $config = array(
            $testClassName => array(
                'arguments' => array(),
                'callbacks' => array('afterInit' => array('initMethod' => array()))
            )
        );
        $expected = $this->getMock('\StdClass', array(), array(), $testClassName);
        $expected2 = $this->getMock('\StdClass', array(), array(), $testClassName);
        $this->factory->expects($this->at(0))->method('createInstance')->will($this->returnValue($expected));
        $this->factory->expects($this->at(1))->method('createInstance')->will($this->returnValue($expected2));
        $this->initServiceLocator($config, $this->factory);
        $actual = $this->serviceLocator->createNewInstance($testClassName);
        $actual2 = $this->serviceLocator->createNewInstance($testClassName);
        $this->assertEquals($expected, $actual);
        $this->assertEquals($expected2, $actual2);
    }

    /**
     * @param $config
     * @param $factory
     */
    public function initServiceLocator($config, $factory)
    {
        $this->serviceLocator = new ServiceLocator($config, $factory);
    }
}
