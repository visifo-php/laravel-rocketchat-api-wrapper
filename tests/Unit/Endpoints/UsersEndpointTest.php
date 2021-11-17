<?php

namespace visifo\Rocket\Tests\Unit\Endpoints;

use Illuminate\Support\Facades\Http;
use visifo\Rocket\Endpoints\Users;
use visifo\Rocket\Objects\Users\User;
use visifo\Rocket\Objects\Users\UserCreate;
use visifo\Rocket\RocketException;
use visifo\Rocket\Tests\ExampleResponseHelper;
use visifo\Rocket\Tests\TestCase;
use function visifo\Rocket\rocketChat;

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
        Http::fake(fn() => Http::response(ExampleResponseHelper::getUsersCreate()));

        $userCreate = new UserCreate('email', 'username', 'name', 'password');
        $result = $this->testSystem->create($userCreate);

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
        Http::fake(fn() => Http::response(ExampleResponseHelper::getSuccessWithoutObject()));
        $this->testSystem->delete('fake_user_id');
        $this->assertTrue(true);
    }

    /**
     * @test
     * @throws RocketException
     */
    public function list_when_validInput_then_succeed()
    {
        Http::fake(fn() => Http::response(ExampleResponseHelper::getUsersList()));

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
        Http::fake(fn() => Http::response(ExampleResponseHelper::getSuccessWithoutObject()));
        $this->testSystem->update('fake_user_id', ['name' => 'newName']);
        $this->assertTrue(true);
    }

    /**
     * @test
     * @throws RocketException
     */
    public function info_when_validInput_then_succeed()
    {
        Http::fake(fn() => Http::response(ExampleResponseHelper::getUsersInfo()));
        $result = $this->testSystem->info('fake_user_id');

        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals('fake_user_id', $result->id);
        $this->assertEquals('fake_user', $result->username);
        $this->assertEquals('user', $result->type);
        $this->assertEquals('offline', $result->status);
        $this->assertEquals(true, $result->active);
    }

    /**
     * @test
     * @throws RocketException
     */
    public function setAvatar_when_validInput_then_succeed()
    {
        Http::fake(fn() => Http::response(ExampleResponseHelper::getSuccessWithoutObject()));
        $this->testSystem->setAvatar('fake_avatar_url', 'userId');
        $this->assertTrue(true);
    }
}
