<?php

namespace visifo\Rocket\Tests\Unit;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use stdClass;
use visifo\Rocket\Rocket;
use visifo\Rocket\RocketException;
use visifo\Rocket\Tests\ExampleResponseHelper;
use visifo\Rocket\Tests\TestCase;
use function visifo\Rocket\rocketChat;

class RocketTest extends TestCase
{
    /** @test */
    public function getInstance_when_creatingInstanceDirect_then_succeed()
    {
        $result = Rocket::getInstance();

        $this->assertNotNull($result);
        $this->assertInstanceOf(Rocket::class, $result);
    }

    /** @test */
    public function getInstance_when_creatingInstanceViaHelper_then_succeed()
    {
        $result = rocketChat();

        $this->assertNotNull($result);
        $this->assertInstanceOf(Rocket::class, $result);
    }

    /** @test */
    public function getInstance_when_creatingInstance_then_headersAreFine()
    {
        $result = rocketChat();

        $expectedHeaders = [
            'X-User-Id' => config('rocket.user.id'),
            'X-Auth-Token' => config('rocket.authToken'),
            'Accept' => 'application/json',
        ];

        $this->assertEquals($expectedHeaders, $result->headers);
    }

    /** @test */
    public function getInstance_when_creatingInstance_then_configIsPresent()
    {
        $result = rocketChat();

        $this->assertNotEmpty($result->url);
        $this->assertNotEmpty($result->userId);
        $this->assertNotEmpty($result->authToken);
        $this->assertNotEmpty($result->userName);
        $this->assertNotEmpty($result->userPassword);
        $this->assertEquals($result->url, config('rocket.url'));
        $this->assertEquals($result->userId, config('rocket.user.id'));
        $this->assertEquals($result->authToken, config('rocket.authToken'));
        $this->assertEquals($result->userName, config('rocket.user.name'));
        $this->assertEquals($result->userPassword, config('rocket.user.password'));
    }

    /**
     * @test
     * @throws RocketException
     */
    public function post_when_minimalValidInput_then_succeed()
    {
        Http::fake(fn() => Http::response(ExampleResponseHelper::successWithObject()));

        $result = rocketChat()->post('fake.endpoint', ['fake' => 'data']);

        $this->assertIsObject($result);
        $this->assertIsObject($result->fakeobject);
        $this->assertEquals('fake', $result->fakeobject->test);
    }

    /**
     * @test
     * @throws RocketException
     */
    public function post_when_emptyData_then_throwException()
    {
        $this->expectException(RocketException::class);
        $this->expectExceptionMessage('post data array cant be empty');

        rocketChat()->post('fake.endpoint', []);
    }

    /** @test
     * @throws RocketException
     */
    public function post_when_statusNot200_then_throwException()
    {
        Http::fake(fn() => Http::response(ExampleResponseHelper::successWithObject(), 401));

        $this->expectException(RequestException::class);
        $this->expectExceptionMessage('HTTP request returned status code 401');

        rocketChat()->post('fake.endpoint', ['fake' => 'data']);
    }

    /** @test */
    public function post_when_successNotSet_then_throwException()
    {
        $this->expectException(RocketException::class);
        $this->expectExceptionMessage("Property: 'success' must be set in RocketChat response");

        Http::fake(fn() => Http::response(['fake' => 'data']));

        rocketChat()->post('fake.endpoint', ['fake' => 'data']);
    }

    /** @test */
    public function post_when_successIsFalse_then_throwException()
    {
        $this->expectException(RocketException::class);
        $this->expectExceptionMessage("Request wasn't successful. Reason: 'FakeError'");

        Http::fake(fn() => Http::response(ExampleResponseHelper::getUnsuccessfulWithException()));

        rocketChat()->post('fake.endpoint', ['fake' => 'data']);
    }

    /** @test
     * @throws RocketException
     */
    public function get_when_validInput_then_succeed()
    {
        Http::fake(fn() => Http::response(ExampleResponseHelper::successWithObject()));

        $result = rocketChat()->get('fake.endpoint');

        $this->assertNotNull($result);
        $this->assertInstanceOf(stdClass::class, $result);
        $this->assertNotNull($result->fakeobject);
        $this->assertEquals('fake', $result->fakeobject->test);
    }

    /** @test
     * @throws RocketException
     */
    public function get_when_statusNot200_then_throwException()
    {
        $this->expectException(RequestException::class);
        $this->expectExceptionMessage('HTTP request returned status code 401:');
        Http::fake(fn() => Http::response(ExampleResponseHelper::successWithObject(), 401));

        rocketChat()->get('fake.endpoint');
    }

    /** @test */
    public function get_when_successNotSet_then_throwException()
    {
        Http::fake(fn() => Http::response(['fake' => 'data']));

        $this->expectException(RocketException::class);
        $this->expectExceptionMessage("Property: 'success' must be set in RocketChat response");

        rocketChat()->get('fake.endpoint');
    }

    /** @test */
    public function get_when_successIsFalse_then_throwException()
    {
        Http::fake(fn() => Http::response(ExampleResponseHelper::getUnsuccessfulWithException()));

        $this->expectException(RocketException::class);
        $this->expectExceptionMessage("FakeError");

        rocketChat()->get('fake.endpoint');
    }
}
