<?php

namespace visifo\Rocket\Tests\Unit\Endpoints;

use Illuminate\Support\Facades\Http;
use visifo\Rocket\Endpoints\Roles;
use visifo\Rocket\Objects\Roles\Role;
use function visifo\Rocket\rocketChat;
use visifo\Rocket\RocketException;
use visifo\Rocket\Tests\ExampleResponseHelper;
use visifo\Rocket\Tests\TestCase;

class RolesEndpointTest extends TestCase
{
    public Roles $testSystem;

    public function setUp(): void
    {
        parent::setUp();
        $this->testSystem = rocketChat()->roles();
    }

    /**
     * @test
     * @throws RocketException
     */
    public function create_when_validInput_then_succeed()
    {
        Http::fake(fn () => Http::response(ExampleResponseHelper::getRolesCreate()));

        $result = $this->testSystem->create('fake_role', 'Subscriptions');

        $this->assertInstanceOf(Role::class, $result);
        $this->assertEquals('fake_role_id', $result->id);
        $this->assertEquals('fake_role_name', $result->name);
        $this->assertEquals('fake_scope', $result->scope);
        $this->assertEquals('fake_description', $result->description);
    }

    /**
     * @test
     * @throws RocketException
     */
    public function create_when_roleAlreadyExists_then_throwException()
    {
        $this->expectException(RocketException::class);
        $this->expectExceptionMessage('FakeError');

        Http::fake(fn () => Http::response(ExampleResponseHelper::getUnsuccessfulWithException()));

        $this->testSystem->create('already_existing_role', 'Subscriptions');
    }
}
