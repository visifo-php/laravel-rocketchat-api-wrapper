<?php
declare(strict_types=1);

namespace visifo\Rocket\Endpoints;

use visifo\Rocket\RocketException;

abstract class Endpoint
{
    /**
     * @throws RocketException
     */
    protected function checkEmptyString(string $s): bool
    {
        if (empty($s)) {
            throw new RocketException("String in function argument cant be empty");
        }
        return false;
    }
}
