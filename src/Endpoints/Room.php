<?php

declare(strict_types=1);

namespace visifo\Rocket\Endpoints;

use visifo\Rocket\Rocket;
use visifo\Rocket\RocketException;

class Room extends Endpoint
{
    private Rocket $rocket;

    public function __construct(Rocket $rocket)
    {
        $this->rocket = $rocket;
    }

    /**
     * @throws RocketException
     */
    public function cleanHistory(string $roomId, string $latest, string $oldest): void
    {
        $this->checkEmptyString($roomId)
            ->checkEmptyString($latest)
            ->checkEmptyString($oldest);

        $data = get_defined_vars();
        $this->rocket->post('rooms.cleanHistory', $data);
    }
}
