<?php

namespace visifo\Rocket\Tests\Unit\Endpoints;

use Illuminate\Support\Facades\Http;
use visifo\Rocket\Endpoints\Chat;
use visifo\Rocket\Objects\Chat\Message;
use visifo\Rocket\Objects\Common\User;
use visifo\Rocket\RocketException;
use visifo\Rocket\Tests\ExampleResponseHelper;
use visifo\Rocket\Tests\TestCase;
use function visifo\Rocket\rocketChat;

class ChatEndpointTest extends TestCase
{
    public Chat $testSystem;

    public function setUp(): void
    {
        parent::setUp();
        $this->testSystem = rocketChat()->chat();
    }

    /**
     * @test
     * @throws RocketException
     */
    public function postChannelMessage_when_validInput_then_succeed()
    {
        Http::fake(fn() => Http::response(ExampleResponseHelper::getChatPostMessage()));
        $result = $this->testSystem->postChannelMessage('fake_room_id', 'test_message');

        $this->assertInstanceOf(Message::class, $result);
        $this->assertEquals('fakeid', $result->id);
        $this->assertEquals('test_message', $result->message);

        $this->assertInstanceOf(User::class, $result->user);
        $this->assertEquals("fakeuser", $result->user->id);
        $this->assertEquals("fakeuser", $result->user->username);
    }

    /**
     * @test
     * @throws RocketException
     */
    public function postRoomMessage_when_validInput_then_succeed()
    {
        Http::fake(fn() => Http::response(ExampleResponseHelper::getChatPostMessage()));
        $result = $this->testSystem->postRoomMessage('fake_room_id', 'test_message');

        $this->assertInstanceOf(Message::class, $result);
        $this->assertEquals('fakeid', $result->id);
        $this->assertEquals('test_message', $result->message);

        $this->assertInstanceOf(User::class, $result->user);
        $this->assertEquals("fakeuser", $result->user->id);
        $this->assertEquals("fakeuser", $result->user->username);
    }

    /**
     * @test
     * @throws RocketException
     */
    public function delete_when_validInput_then_succeed()
    {
        Http::fake(fn() => Http::response(ExampleResponseHelper::getSuccessWithoutObject()));
        $this->testSystem->delete('fake_room_id', 'fake_message_id');
        $this->assertTrue(true);
    }

    /**
     * @test
     * @throws RocketException
     */
    public function getMessage_when_validInput_then_succeed()
    {
        Http::fake(fn() => Http::response(ExampleResponseHelper::getChatGetMessage()));
        $result = $this->testSystem->getMessage('fake_room_id');

        $this->assertInstanceOf(Message::class, $result);
        $this->assertEquals('fake_message_id', $result->id);
        $this->assertEquals('fake_message', $result->message);
        $this->assertEquals('fake_channel_id', $result->channelId);

        $this->assertInstanceOf(User::class, $result->user);
        $this->assertEquals("fake_user_id", $result->user->id);
        $this->assertEquals("fake_username", $result->user->username);
    }

    /**
     * @test
     * @throws RocketException
     */
    public function pinMessage_when_validInput_then_succeed()
    {
        Http::fake(fn() => Http::response(ExampleResponseHelper::getSuccessWithoutObject()));
        $this->testSystem->pinMessage('fake_message_id');
        $this->assertTrue(true);
    }

    /**
     * @test
     * @throws RocketException
     */
    public function starMessage_when_validInput_then_succeed()
    {
        Http::fake(fn() => Http::response(ExampleResponseHelper::getSuccessWithoutObject()));
        $this->testSystem->starMessage('fake_message_id');
        $this->assertTrue(true);
    }

    /**
     * @test
     * @throws RocketException
     */
    public function react_when_validInput_then_succeed()
    {
        Http::fake(fn() => Http::response(ExampleResponseHelper::getSuccessWithoutObject()));
        $this->testSystem->react('fake_message_id', 'smile');
        $this->assertTrue(true);
    }

    /**
     * @test
     * @throws RocketException
     */
    public function unfollowMessage_when_validInput_then_succeed()
    {
        Http::fake(fn() => Http::response(ExampleResponseHelper::getSuccessWithoutObject()));
        $this->testSystem->unfollowMessage('fake_message_id');
        $this->assertTrue(true);
    }

    /**
     * @test
     * @throws RocketException
     */
    public function unPinMessage_when_validInput_then_succeed()
    {
        Http::fake(fn() => Http::response(ExampleResponseHelper::getSuccessWithoutObject()));
        $this->testSystem->unPinMessage('fake_message_id');
        $this->assertTrue(true);
    }
}
