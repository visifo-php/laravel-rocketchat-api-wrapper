<?php

namespace visifo\Rocket;

use Exception;
use Throwable;

class RocketException extends Exception
{
    public ?string $errorType;

    public function __construct(string $message, int $code = 0, Throwable $previous = null, string $errorType = null)
    {
        parent::__construct($message, $code, $previous);
        $this->errorType = $errorType;
    }
}
