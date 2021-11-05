<?php

namespace visifo\Rocket\Commands;

use Illuminate\Console\Command;

class RocketCommand extends Command
{
    public $signature = 'laravel-rocketchat-api-wrapper';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
