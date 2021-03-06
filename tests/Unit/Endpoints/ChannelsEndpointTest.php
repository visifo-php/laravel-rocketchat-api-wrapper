<?php

namespace visifo\Rocket\Tests\Unit\Endpoints;

use Illuminate\Support\Facades\Http;
use visifo\Rocket\Endpoints\Channels;
use visifo\Rocket\Enums\ChannelType;
use visifo\Rocket\Objects\Channels\Channel;
use visifo\Rocket\RocketException;
use visifo\Rocket\Tests\ExampleResponseHelper;
use visifo\Rocket\Tests\TestCase;
use function visifo\Rocket\rocketChat;

class ChannelsEndpointTest extends TestCase
{
    public Channels $testSystem;

    public function setUp(): void
    {
        parent::setUp();
        $this->testSystem = rocketChat()->channels();
    }

    /**
     * @test
     * @throws RocketException
     */
    public function create_when_channelAlreadyExist_then_throwException()
    {
        $this->expectException(RocketException::class);
        $this->expectExceptionMessage("FakeError");

        Http::fake(fn() => Http::response(ExampleResponseHelper::getUnsuccessfulWithException()));
        $this->testSystem->create('fake');
    }

    /**
     * @test
     * @throws RocketException
     */
    public function create_when_validInput_then_succeed()
    {
        Http::fake(fn() => Http::response(ExampleResponseHelper::getChannelsCreate()));
        $result = $this->testSystem->create('fake_channel_name');

        $this->assertInstanceOf(Channel::class, $result);
        $this->assertEquals('fake_channel_id', $result->id);
        $this->assertEquals('fake_channel_name', $result->name);
        $this->assertEquals('c', $result->type);
    }

    /**
     * @test
     * @throws RocketException
     */
    public function info_when_everythingFine_then_succeed()
    {
        Http::fake(fn() => Http::response(ExampleResponseHelper::getChannelsInfo()));
        $result = $this->testSystem->info('fake_id');

        $this->assertInstanceOf(Channel::class, $result);
        $this->assertEquals('fake_channel_id', $result->id);
        $this->assertEquals('fake_channel_name', $result->name);
        $this->assertEquals('c', $result->type);
    }

    /**
     * @test
     * @throws RocketException
     */
    public function delete_when_roomExists_then_succeed()
    {
        Http::fake(fn() => Http::response(ExampleResponseHelper::getSuccessWithoutObject()));
        $this->testSystem->delete('room_id');
        $this->assertTrue(true);
    }

    /**
     * @test
     * @throws RocketException
     */
    public function setTopic_when_validInput_then_succeed()
    {
        Http::fake(fn() => Http::response(ExampleResponseHelper::getSuccessWithoutObject()));
        $this->testSystem->setTopic('room_id', 'fake_topic');
        $this->assertTrue(true);
    }

    /**
     * @test
     * @throws RocketException
     */
    public function addAll_when_validInput_then_succeed()
    {
        Http::fake(fn() => Http::response(ExampleResponseHelper::getSuccessWithoutObject()));
        $this->testSystem->addAll('room_id');
        $this->assertTrue(true);
    }

    /**
     * @test
     * @throws RocketException
     */
    public function addLeader_when_validInput_then_succeed()
    {
        Http::fake(fn() => Http::response(ExampleResponseHelper::getSuccessWithoutObject()));
        $this->testSystem->addLeader('room_id', 'user_id');
        $this->assertTrue(true);
    }

    /**
     * @test
     * @throws RocketException
     */
    public function addModerator_when_validInput_then_succeed()
    {
        Http::fake(fn() => Http::response(ExampleResponseHelper::getSuccessWithoutObject()));
        $this->testSystem->addModerator('room_id', 'user_id');
        $this->assertTrue(true);
    }

    /**
     * @test
     * @throws RocketException
     */
    public function addOwner_when_validInput_then_succeed()
    {
        Http::fake(fn() => Http::response(ExampleResponseHelper::getSuccessWithoutObject()));
        $this->testSystem->addOwner('room_id', 'user_id');
        $this->assertTrue(true);
    }

    /**
     * @test
     * @throws RocketException
     */
    public function archive_when_validInput_then_succeed()
    {
        Http::fake(fn() => Http::response(ExampleResponseHelper::getSuccessWithoutObject()));
        $this->testSystem->archive('room_id');
        $this->assertTrue(true);
    }

    /**
     * @test
     * @throws RocketException
     */
    public function close_when_validInput_then_succeed()
    {
        Http::fake(fn() => Http::response(ExampleResponseHelper::getSuccessWithoutObject()));
        $this->testSystem->close('room_id');
        $this->assertTrue(true);
    }

    /**
     * @test
     * @throws RocketException
     */
    public function invite_when_validInput_then_succeed()
    {
        Http::fake(fn() => Http::response(ExampleResponseHelper::getSuccessWithoutObject()));
        $this->testSystem->invite('room_id', 'user_id');
        $this->assertTrue(true);
    }

    /**
     * @test
     * @throws RocketException
     */
    public function join_when_validInput_then_succeed()
    {
        Http::fake(fn() => Http::response(ExampleResponseHelper::getSuccessWithoutObject()));
        $this->testSystem->join('room_id');
        $this->assertTrue(true);
    }

    /**
     * @test
     * @throws RocketException
     */
    public function kick_when_validInput_then_succeed()
    {
        Http::fake(fn() => Http::response(ExampleResponseHelper::getSuccessWithoutObject()));
        $this->testSystem->kick('room_id', 'user_id');
        $this->assertTrue(true);
    }

    /**
     * @test
     * @throws RocketException
     */
    public function leave_when_validInput_then_succeed()
    {
        Http::fake(fn() => Http::response(ExampleResponseHelper::getSuccessWithoutObject()));
        $this->testSystem->leave('room_id');
        $this->assertTrue(true);
    }

    /**
     * @test
     * @throws RocketException
     */
    public function open_when_everythingFine_then_succeed()
    {
        Http::fake(fn() => Http::response(ExampleResponseHelper::getSuccessWithoutObject()));
        $this->testSystem->open('room_id');
        $this->assertTrue(true);
    }

    /**
     * @test
     * @throws RocketException
     */
    public function removeLeader_when_everythingFine_then_succeed()
    {
        Http::fake(fn() => Http::response(ExampleResponseHelper::getSuccessWithoutObject()));
        $this->testSystem->removeLeader('room_id', 'user_id');
        $this->assertTrue(true);
    }

    /**
     * @test
     * @throws RocketException
     */
    public function removeModerator_when_everythingFine_then_succeed()
    {
        Http::fake(fn() => Http::response(ExampleResponseHelper::getSuccessWithoutObject()));
        $this->testSystem->removeModerator('room_id', 'user_id');
        $this->assertTrue(true);
    }

    /**
     * @test
     * @throws RocketException
     */
    public function removeOwner_when_everythingFine_then_succeed()
    {
        Http::fake(fn() => Http::response(ExampleResponseHelper::getSuccessWithoutObject()));
        $this->testSystem->removeOwner('room_id', 'user_id');
        $this->assertTrue(true);
    }

    /**
     * @test
     * @throws RocketException
     */
    public function rename_when_everythingFine_then_succeed()
    {
        Http::fake(fn() => Http::response(ExampleResponseHelper::getSuccessWithoutObject()));
        $this->testSystem->rename('room_id', 'fake_name');
        $this->assertTrue(true);
    }

    /**
     * @test
     * @throws RocketException
     */
    public function setAnnouncement_when_everythingFine_then_succeed()
    {
        Http::fake(fn() => Http::response(ExampleResponseHelper::getSuccessWithoutObject()));
        $this->testSystem->setAnnouncement('room_id', 'fake_announcement');
        $this->assertTrue(true);
    }

    /**
     * @test
     * @throws RocketException
     */
    public function setDefault_when_everythingFine_then_succeed()
    {
        Http::fake(fn() => Http::response(ExampleResponseHelper::getSuccessWithoutObject()));
        $this->testSystem->setDefault('room_id', 'fake_default');
        $this->assertTrue(true);
    }

    /**
     * @test
     * @throws RocketException
     */
    public function setDescription_when_everythingFine_then_succeed()
    {
        Http::fake(fn() => Http::response(ExampleResponseHelper::getSuccessWithoutObject()));
        $this->testSystem->setDescription('room_id', 'fake_description');
        $this->assertTrue(true);
    }

    /**
     * @test
     * @throws RocketException
     */
    public function setJoinCode_when_everythingFine_then_succeed()
    {
        Http::fake(fn() => Http::response(ExampleResponseHelper::getSuccessWithoutObject()));
        $this->testSystem->setJoinCode('room_id', 'fake_joincode');
        $this->assertTrue(true);
    }

    /**
     * @test
     * @throws RocketException
     */
    public function setPurpose_when_everythingFine_then_succeed()
    {
        Http::fake(fn() => Http::response(ExampleResponseHelper::getSuccessWithoutObject()));
        $this->testSystem->setPurpose('room_id', 'fake_purpose');
        $this->assertTrue(true);
    }

    /**
     * @test
     * @throws RocketException
     */
    public function setReadOnly_when_everythingFine_then_succeed()
    {
        Http::fake(fn() => Http::response(ExampleResponseHelper::getSuccessWithoutObject()));
        $this->testSystem->setReadOnly('room_id', true);
        $this->assertTrue(true);
    }

    /**
     * @test
     * @throws RocketException
     */
    public function setType_when_everythingFine_then_succeed()
    {
        Http::fake(fn() => Http::response(ExampleResponseHelper::getSuccessWithoutObject()));
        $this->testSystem->setType(ChannelType::Channel(), 'room_id');
        $this->assertTrue(true);
    }

    /**
     * @test
     * @throws RocketException
     */
    public function unarchive_when_everythingFine_then_succeed()
    {
        Http::fake(fn() => Http::response(ExampleResponseHelper::getSuccessWithoutObject()));
        $this->testSystem->unarchive('room_id');
        $this->assertTrue(true);
    }

    /**
     * @test
     * @throws RocketException
     */
    public function list_when_everythingFine_then_succeed()
    {
        Http::fake(fn() => Http::response(ExampleResponseHelper::getChannelsList()));
        $result = $this->testSystem->list(200, 0);

        $this->assertInstanceOf(\visifo\Rocket\Objects\Channels\Channels::class, $result);
        $this->assertEquals('ByehQjC44FwMeiLbX', $result->channels[0]->id);
        $this->assertEquals('channel_1', $result->channels[0]->name);
        $this->assertEquals('c', $result->channels[0]->type);
    }
}
