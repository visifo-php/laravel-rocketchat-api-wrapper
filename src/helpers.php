<?php

namespace visifo\Rocket;

if (! function_exists('rocketChat')) {
    function rocketChat(): Rocket
    {
        return Rocket::getInstance();
    }
}
