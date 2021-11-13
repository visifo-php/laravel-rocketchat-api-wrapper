<?php

namespace visifo\Rocket\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;
use visifo\Rocket\RocketServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'visifo\\Rocket\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app): array
    {
        return [
            RocketServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
        config()->set('rocket.url', 'www.example.com');
        config()->set('rocket.authToken', '123456');
        config()->set('rocket.user.id', '654321');
        /*
        $migration = include __DIR__.'/../database/migrations/create_laravel-rocketchat-api-wrapper_table.php.stub';
        $migration->up();
        */
    }
}
