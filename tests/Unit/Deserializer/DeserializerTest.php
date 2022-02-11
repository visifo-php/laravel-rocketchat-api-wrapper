<?php

namespace visifo\Rocket\Tests\Unit\Deserializer;

use stdClass;
use visifo\Rocket\Deserializer;
use visifo\Rocket\Objects\Users\User;
use visifo\Rocket\RocketException;
use visifo\Rocket\Tests\ExampleResponseHelper;
use visifo\Rocket\Tests\TestCase;

class DeserializerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @test
     */
    public function deserialize_when_unsuccessfulResponse_then_throwException()
    {
        $this->expectException(RocketException::class);
        $this->expectExceptionMessage("Didnt find any objects/arrays inside RocketChat response");

        $response = new stdClass();

        Deserializer::deserializeObject($response, UntypedPropertyClass::class);
    }

    /**
     * @test
     */
    public function deserialize_when_noObjectInResponseFound_then_throwException()
    {
        $this->expectException(RocketException::class);
        $this->expectExceptionMessage('Didnt find any objects/arrays inside RocketChat response');

        $response = new stdClass();
        $response->success = true;

        Deserializer::deserializeObject($response, UntypedPropertyClass::class);
    }

    /**
     * @test
     */
    public function deserialize_when_moreThanOneObjectInResponseFound_then_throwException()
    {
        $this->expectException(RocketException::class);
        $this->expectExceptionMessage('You can only receive 1 object/array from RocketChat');

        $response = new stdClass();
        $response->success = true;
        $response->objectA = new stdClass();
        $response->objectB = new stdClass();

        Deserializer::deserializeObject($response, UntypedPropertyClass::class);
    }

    /**
     * @test
     */
    public function deserialize_when_propertyDoesNotExistInResponse_then_throwException()
    {
        $this->expectException(RocketException::class);
        $this->expectExceptionMessage("property: 'test' must exist in response");

        $response = new stdClass();
        $response->success = true;
        $response->test = new stdClass();

        Deserializer::deserializeObject($response, UntypedPropertyClass::class);
    }

    /**
     * @test
     */
    public function deserialize_when_propertyIsNotTyped_then_throwException()
    {
        $this->expectException(RocketException::class);
        $this->expectExceptionMessage("property: 'test' needs to be typed");

        $response = new stdClass();
        $response->success = true;
        $response->test = new stdClass();
        $response->test->test = "test";

        Deserializer::deserializeObject($response, UntypedPropertyClass::class);
    }

    /**
     * @test
     */
    public function deserialize_when_mappedPropertyDoesNotExist_then_throwException()
    {
        $this->expectException(RocketException::class);
        $this->expectExceptionMessage("The mapped property 'does_not_exist' for 'test' does not exist in RocketChat Response");

        $response = new stdClass();
        $response->success = true;
        $response->test = new stdClass();
        $response->test->test = "test";

        Deserializer::deserializeObject($response, MappedPropertyDoesNotExistClass::class);
    }

    /**
     * @test
     * @throws RocketException
     */
    public function deserialize_when_validInput_then_throwException()
    {
        $object = ExampleResponseHelper::getUsersCreateAsObject();
        $result = Deserializer::deserializeObject($object, User::class);

        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals('fake_user_id', $result->id);
        $this->assertEquals('fake_user_name', $result->username);
        $this->assertEquals('offline', $result->status);
        $this->assertEquals(true, $result->active);
    }
}
