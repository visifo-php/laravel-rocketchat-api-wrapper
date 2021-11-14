<?php

namespace visifo\Rocket\Tests\Unit\Endpoints;

use Illuminate\Support\Facades\Http;
use ReflectionClass;
use ReflectionException;
use visifo\Rocket\Endpoints\Users;
use function visifo\Rocket\rocketChat;
use visifo\Rocket\RocketException;
use visifo\Rocket\Tests\ExampleResponseHelper;
use visifo\Rocket\Tests\TestCase;

class UpdateUsersWhenNoPasswordSetTest extends TestCase
{
    public Users $testSystem;

    public function setUp(): void
    {
        parent::setUp();
        $this->testSystem = rocketChat()->users();
    }

    /**
     * @test
     * @throws RocketException
     * @throws ReflectionException
     */
    public function update_when_noPasswordSet_then_throwException()
    {
        $this->expectException(RocketException::class);
        $this->expectExceptionMessage('Password required for 2FA requests. Please set it in your Laravel .env file');
        Http::fake(fn () => Http::response(ExampleResponseHelper::getSuccessWithoutObject()));

        $resultReflection = new ReflectionClass(rocketChat());
        $resultPassword = $resultReflection->getProperty('password');
        $resultPassword->setAccessible(true);
        $resultPassword->setValue(rocketChat(), '');

        $this->testSystem->update('fake_user_id', ['name' => 'newName']);

        $resultPassword->setValue(rocketChat(), 'password');
    }
}
