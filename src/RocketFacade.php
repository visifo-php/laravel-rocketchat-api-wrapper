<?php

namespace visifo\Rocket;

use Illuminate\Support\Facades\Facade;

/**
 * @see \visifo\Rocket\Rocket
 */
class RocketFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-rocketchat-api-wrapper';
    }
}
