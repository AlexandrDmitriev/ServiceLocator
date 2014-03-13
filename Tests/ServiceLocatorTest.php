<?php

namespace ServiceLocator\Tests;

use ServiceLocator\ServiceLocator;

class ServiceLocatorTest extends \PHPUnit_Framework_TestCase
{
    private $serviceLocator;

    public function setUp()
    {
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
        $factory = $this->getMock('\ServiceLocator\AbstractFactory');
        $this->serviceLocator = new ServiceLocator($config, $factory);
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
        $factory = $this->getMock('\ServiceLocator\AbstractFactory');
        $expected = new \StdClass();
        $factory->expects($this->once())->method('createInstance')->will($this->returnValue($expected));
        $this->serviceLocator = new ServiceLocator($config, $factory);
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
        $factory = $this->getMock('\ServiceLocator\AbstractFactory');
        $expected = new \StdClass();
        $factory->expects($this->once())->method('createInstance')->will($this->returnValue($expected));
        $this->serviceLocator = new ServiceLocator($config, $factory);
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
        $factory = $this->getMock('\ServiceLocator\AbstractFactory');
        $this->serviceLocator = new ServiceLocator($config, $factory);
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
        $factory = $this->getMock('\ServiceLocator\AbstractFactory');
        $factory->expects($this->never())->method('createInstance');
        $this->serviceLocator = new ServiceLocator($config, $factory);
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
        $factory = $this->getMock('\ServiceLocator\AbstractFactory');
        $expected = new \StdClass();
        $factory->expects($this->once())->method('createInstance')->will($this->returnValue($expected));
        $this->serviceLocator = new ServiceLocator($config, $factory);
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
        $factory = $this->getMock('\ServiceLocator\AbstractFactory');
        $expected = $this->getMock('\StdClass', array(), array(), $testClassName);
        $expected2 = $this->getMock('\StdClass', array(), array(), $testClassName);
        $factory->expects($this->at(0))->method('createInstance')->will($this->returnValue($expected));
        $factory->expects($this->at(1))->method('createInstance')->will($this->returnValue($expected2));
        $this->serviceLocator = new ServiceLocator($config, $factory);
        $actual = $this->serviceLocator->createNewInstance($testClassName);
        $actual2 = $this->serviceLocator->createNewInstance($testClassName);
        $this->assertEquals($expected, $actual);
        $this->assertEquals($expected2, $actual2);
    }
}
