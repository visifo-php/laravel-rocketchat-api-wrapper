<?php

namespace visifo\Rocket;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use visifo\Rocket\Commands\RocketCommand;

class RocketServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-rocketchat-api-wrapper')
            ->hasConfigFile('rocket')
            ->hasCommand(RocketCommand::class);
    }
}
