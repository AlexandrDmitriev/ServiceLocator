<?php

namespace ServiceLocator\Tests;

class AbstractFactoryStubClass
{
    public $params;

    public function init($params)
    {
        $this->params = $params;
    }
}
