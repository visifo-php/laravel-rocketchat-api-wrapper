<?php

declare(strict_types=1);

namespace visifo\Rocket\Endpoints;

use visifo\Rocket\Deserializer;
use visifo\Rocket\Objects\Commands\Command;
use visifo\Rocket\Rocket;
use visifo\Rocket\RocketException;

class Commands extends Endpoint
{
    private Rocket $rocket;

    public function __construct(Rocket $rocket)
    {
        $this->rocket = $rocket;
    }

    /**
     * @throws RocketException
     */
    public function get(string $command): Command
    {
        $this->checkEmptyString($command);
        $query = get_defined_vars();
        $response = $this->rocket->get("commands.get", $query);

        return Deserializer::deserialize($response, Command::class);
    }

    /**
     * @throws RocketException
     */
    public function list(): \visifo\Rocket\Objects\Commands\Commands
    {
        $response = $this->rocket->get("commands.list");

        return Deserializer::deserialize($response, \visifo\Rocket\Objects\Commands\Commands::class);
    }

    /**
     * @throws RocketException
     */
    public function run(string $command, string $roomId, string $params = ''): void
    {
        $this
            ->checkEmptyString($command)
            ->checkEmptyString($roomId);

        if (empty($params)) {
            unset($params);
        } else {
            $this->checkEmptyString($params);
        }

        $data = get_defined_vars();
        $this->rocket->post("commands.run", $data);
    }
}
