<?php

namespace visifo\Rocket\Tests\Unit\Endpoints;

use Illuminate\Support\Facades\Http;
use ReflectionException;
use visifo\Rocket\Endpoints\Users;
use visifo\Rocket\Objects\Users\User;
use function visifo\Rocket\rocketChat;
use visifo\Rocket\RocketException;
use visifo\Rocket\Tests\ExampleResponseHelper;
use visifo\Rocket\Tests\TestCase;

class UsersEndpointTest extends TestCase
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
     */
    public function create_when_validInput_then_succeed()
    {
        Http::fake(fn () => Http::response(ExampleResponseHelper::getUsersCreate()));

        $result = $this->testSystem->create('fake_channel_name');

        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals('fake_user_id', $result->id);
        $this->assertEquals('fake_user_name', $result->username);
        $this->assertEquals('user', $result->type);
        $this->assertEquals('offline', $result->status);
        $this->assertEquals(true, $result->active);
    }

    /**
     * @test
     * @throws RocketException
     */
    public function delete_when_validInput_then_succeed()
    {
        $this->expectNotToPerformAssertions();

        Http::fake(fn () => Http::response(ExampleResponseHelper::getSuccessWithoutObject()));

        $this->testSystem->delete('fake_user_id');
    }

    /**
     * @test
     * @throws RocketException
     */
    public function list_when_validInput_then_succeed()
    {
        Http::fake(fn () => Http::response(ExampleResponseHelper::getUsersList()));

        $result = $this->testSystem->list();

        $this->assertInstanceOf(\visifo\Rocket\Objects\Users\Users::class, $result);
        $this->assertEquals('fake_user_id', $result->users[0]->id);
        $this->assertEquals('fake_type', $result->users[0]->type);
        $this->assertEquals("offline", $result->users[0]->status);
        $this->assertEquals(false, $result->users[0]->active);
        $this->assertEquals('fake_user_name', $result->users[0]->username);
    }

    /**
     * @test
     * @throws RocketException
     */
    public function update_when_validInput_then_succeed()
    {
        $this->expectNotToPerformAssertions();

        Http::fake(fn () => Http::response(ExampleResponseHelper::getSuccessWithoutObject()));

        $this->testSystem->update('fake_user_id', ['name' => 'newName']);
    }
}
