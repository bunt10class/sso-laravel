<?php

namespace Edu\Sso\Exceptions;

use Exception;

class SsoNotExistsMethodException extends Exception
{
    public function __construct(string $class, string $method, string $description)
    {
        parent::__construct('Class: "' . $class . '" doesn\'t have method: "' . $method . '". Check your sso config, that class need to ' . $description);
    }
}
