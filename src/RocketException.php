<?php

namespace visifo\Rocket;

use Exception;
use Throwable;

class RocketException extends Exception
{
    public string $errorType;

    public function __construct($message = "", $errorType = "", Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
        $this->errorType = $errorType;
    }
}
