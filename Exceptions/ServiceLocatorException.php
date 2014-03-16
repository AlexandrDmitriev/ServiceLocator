<?php

namespace ServiceLocator\Exceptions;

class ServiceLocatorException extends \Exception
{
    const ERROR_CODE = 600;

    public function __construct($message)
    {
        parent::__construct("Exception in service locator with message: $message", ServiceLocatorException::ERROR_CODE);
    }
}
