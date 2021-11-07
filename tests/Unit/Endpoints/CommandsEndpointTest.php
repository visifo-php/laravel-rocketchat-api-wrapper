<?php

namespace visifo\Rocket\Tests\Unit\Endpoints;

use Illuminate\Support\Facades\Http;
use visifo\Rocket\Endpoints\Commands;
use visifo\Rocket\Objects\Commands\Command;
use function visifo\Rocket\rocketChat;
use visifo\Rocket\RocketException;
use visifo\Rocket\Tests\ExampleResponseHelper;
use visifo\Rocket\Tests\TestCase;

class CommandsEndpointTest extends TestCase
{
    public Commands $testSystem;

    public function setUp(): void
    {
        parent::setUp();
        $this->testSystem = rocketChat()->commands;
    }

    /**
     * @test
     * @throws RocketException
     */
    public function get_when_commandNotExists_then_throwException()
    {
        $this->expectException(RocketException::class);
        $this->expectExceptionMessage('FakeError');

        Http::fake(fn () => Http::response(ExampleResponseHelper::getUnsuccessfulWithException()));

        $result = $this->testSystem->get('not_existing_command');
    }

    /**
     * @test
     * @throws RocketException
     */
    public function get_when_validInput_then_succeed()
    {
        Http::fake(fn () => Http::response(ExampleResponseHelper::getCommandsGet()));

        $result = $this->testSystem->get('fake_command');

        $this->assertInstanceOf(Command::class, $result);
        $this->assertEquals('fake_command', $result->command);
        $this->assertEquals('fake_params', $result->params);
        $this->assertEquals('fake_description', $result->description);
        $this->assertEquals(false, $result->clientOnly);
    }

    /**
     * @test
     * @throws RocketException
     */
    public function list_when_validInput_then_succeed()
    {
        Http::fake(fn () => Http::response(ExampleResponseHelper::getCommandsList()));

        $result = $this->testSystem->list();

        $this->assertInstanceOf(\visifo\Rocket\Objects\Commands\Commands::class, $result);
        $this->assertEquals('fake_command1', $result->commands[0]->command);
        $this->assertEquals(false, $result->commands[0]->clientOnly);
        $this->assertEquals('fake_command2', $result->commands[1]->command);
        $this->assertEquals(false, $result->commands[1]->clientOnly);
        $this->assertEquals('fake_params', $result->commands[1]->params);
        $this->assertEquals('fake_description', $result->commands[1]->description);
    }

    /**
     * @test
     * @throws RocketException
     */
    public function run_when_validInput_then_succeed()
    {
        Http::fake(fn () => Http::response(ExampleResponseHelper::getSuccessWithoutObject()));

        $this->testSystem->run('fake_command1', 'fake_room_id');

        $this->expectNotToPerformAssertions();
    }
}
