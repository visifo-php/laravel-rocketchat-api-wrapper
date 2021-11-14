<?php

namespace visifo\Rocket\Tests\Unit\Endpoints;

use Illuminate\Support\Facades\Http;
use visifo\Rocket\Endpoints\Users;
use visifo\Rocket\RocketException;
use visifo\Rocket\Tests\ExampleResponseHelper;
use visifo\Rocket\Tests\TestCase;
use function visifo\Rocket\rocketChat;

class UpdateUsersWhenNoPasswordSetTest extends TestCase
{
    public Users $testSystem;

    public function setUp(): void
    {
        parent::setUp();
        $this->testSystem = rocketChat()->users();
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('rocket.url', 'www.example.com');
        config()->set('rocket.authToken', 'Z9__Y1_Es6OB2kMf4dBD3I6qygRT3s-Lla67pf8AU1p');
        config()->set('rocket.user.id', 'RLhxwWHn9RjiWjtdG');
        config()->set('rocket.user.password', '');
    }

    /**
     * @test
     * @throws RocketException
     */
    public function update_when_noPasswordSet_then_throwException()
    {
        $this->expectException(RocketException::class);
        $this->expectExceptionMessage('Password required for 2FA requests. Please set it in your Laravel .env file');
        Http::fake(fn() => Http::response(ExampleResponseHelper::getSuccessWithoutObject()));

        $this->testSystem->update('fake_user_id', ['name' => 'newName']);
    }
}
