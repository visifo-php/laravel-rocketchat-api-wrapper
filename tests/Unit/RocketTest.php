<?php

namespace visifo\Rocket\Tests\Unit;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use ReflectionClass;
use ReflectionException;
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

    /** @test
     * @throws ReflectionException
     */
    public function getInstance_when_creatingInstance_then_headersAreFine()
    {
        $result = rocketChat();

        $expectedHeaders = [
            'X-User-Id' => 'RLhxwWHn9RjiWjtdG',
            'X-Auth-Token' => 'Z9__Y1_Es6OB2kMf4dBD3I6qygRT3s-Lla67pf8AU1p',
            'Accept' => 'application/json',
        ];

        $resultReflection = new ReflectionClass($result);

        $resultHeaders = $resultReflection->getProperty('headers');
        $resultHeaders->setAccessible(true);

        $this->assertEquals($expectedHeaders, $resultHeaders->getValue($result));
    }

    /** @test
     * @throws ReflectionException
     */
    public function getInstance_when_creatingInstance_then_configIsPresent()
    {
        $result = rocketChat();
        $resultReflection = new ReflectionClass($result);

        $resultUrl = $resultReflection->getProperty('url');
        $resultUserId = $resultReflection->getProperty('userId');
        $resultUserPassword = $resultReflection->getProperty('password');
        $resultAuthToken = $resultReflection->getProperty('authToken');

        $resultUrl->setAccessible(true);
        $resultUserId->setAccessible(true);
        $resultUserPassword->setAccessible(true);
        $resultAuthToken->setAccessible(true);

        $this->assertNotEmpty($resultUrl->getValue($result));
        $this->assertNotEmpty($resultUserId->getValue($result));
        $this->assertNotEmpty($resultUserPassword->getValue($result));
        $this->assertNotEmpty($resultAuthToken->getValue($result));

        $this->assertEquals('www.example.com', $resultUrl->getValue($result));
        $this->assertEquals('RLhxwWHn9RjiWjtdG', $resultUserId->getValue($result));
        $this->assertEquals('Z9__Y1_Es6OB2kMf4dBD3I6qygRT3s-Lla67pf8AU1p', $resultAuthToken->getValue($result));
        $this->assertEquals('password', $resultUserPassword->getValue($result));
    }

    /**
     * @test
     * @throws RocketException
     */
    public function post_when_minimalValidInput_then_succeed()
    {
        Http::fake(fn() => Http::response(ExampleResponseHelper::successWithObject()));
        $result = rocketChat()->post('fake.endpoint', ['fake' => 'data']);

        $this->assertInstanceOf(Response::class, $result);
        $this->assertIsObject($result->object());
        $this->assertIsObject($result->object()->fakeobject);
        $this->assertEquals('fake', $result->object()->fakeobject->test);
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
        Http::fake(fn() => Http::response(ExampleResponseHelper::getUnsuccessfulWithException(), 401));

        $this->expectException(RocketException::class);
        $this->expectExceptionCode(401);
        $this->expectExceptionMessage('FakeError');

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
        $this->assertInstanceOf(Response::class, $result);
        $this->assertInstanceOf(stdClass::class, $result->object());
        $this->assertNotNull($result->object()->fakeobject);
        $this->assertEquals('fake', $result->object()->fakeobject->test);
    }

    /** @test
     * @throws RocketException
     */
    public function get_when_statusNot200_then_throwException()
    {
        $this->expectException(RocketException::class);
        $this->expectExceptionCode(401);
        $this->expectExceptionMessage('{"fakeobject":{"test":"fake"},"success":true}');

        Http::fake(fn() => Http::response(ExampleResponseHelper::successWithObject(), 401));
        rocketChat()->get('fake.endpoint');
    }

    /** @test */
    public function get_when_successNotSet_then_throwException()
    {
        $this->expectException(RocketException::class);
        $this->expectExceptionMessage("Property: 'success' must be set in RocketChat response");

        Http::fake(fn() => Http::response(['fake' => 'data']));
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
