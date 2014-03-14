<?php

namespace ServiceLocator\Utility;

use ServiceLocator\Exceptions\ServiceLocatorException;

abstract class BaseAccessor
{
    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        }

        throw new ServiceLocatorException("Can not return protected or private property: {$name}. It is not exist.");
    }
}
