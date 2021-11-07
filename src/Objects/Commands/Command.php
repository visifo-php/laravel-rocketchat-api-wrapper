<?php

namespace visifo\Rocket\Objects\Commands;

class Command
{
    public string $command;
    /** @nullable */
    public ?string $params;
    /** @nullable */
    public ?string $description;
    public bool $clientOnly;
}
