<?php

namespace ServiceLocator\Tests;

use ServiceLocator\Entity\ServiceCallBack;

class BaseAccessorTest extends \PHPUnit_Framework_TestCase
{
    public function testBaseAccessorShouldReturnProtectedPropertyValue()
    {
        $expectedMethod = 'expectedMethod';
        $expectedArgs = array('expectedArg');

        $accessor = new ServiceCallBack($expectedMethod, $expectedArgs);

        $this->assertEquals($expectedMethod, $accessor->methodName);
        $this->assertEquals($expectedArgs, $accessor->methodArgs);
    }

    /**
     * @expectedException \ServiceLocator\Exceptions\ServiceLocatorException
     */
    public function testBaseAccessorShouldThrowExceptionIfPropertyNotExist()
    {
        $expectedMethod = 'expectedMethod';
        $expectedArgs = array('expectedArg');

        $accessor = new ServiceCallBack($expectedMethod, $expectedArgs);
        $accessor->notExistPropertyName;
    }
}
