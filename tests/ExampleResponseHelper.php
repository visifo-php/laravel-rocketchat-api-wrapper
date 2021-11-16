<?php

namespace visifo\Rocket\Tests;

use visifo\Rocket\RocketException;

class ExampleResponseHelper
{
    /**
     * @throws RocketException
     */
    public static function getChannelsCreate(): array
    {
        return self::getJson(__DIR__ . '/Responses/Channels/Create.json');
    }

    /**
     * @throws RocketException
     */
    public static function getChannelsInfo(): array
    {
        return self::getJson(__DIR__ . '/Responses/Channels/Create.json');
    }

    /**
     * @throws RocketException
     */
    public static function getChannelsDelete(): array
    {
        return self::getJson(__DIR__ . '/Responses/Channels/Info.json');
    }

    /**
     * @throws RocketException
     */
    public static function getChannelsAddLeader(): array
    {
        return self::getJson(__DIR__ . '/Responses/Channels/Info.json');
    }

    /**
     * @throws RocketException
     */
    public static function getChatPostMessage(): array
    {
        return self::getJson(__DIR__ . '/Responses/Chat/PostMessage.json');
    }

    /**
     * @throws RocketException
     */
    public static function getChatGetMessage(): array
    {
        return self::getJson(__DIR__ . '/Responses/Chat/GetMessage.json');
    }

    /**
     * @throws RocketException
     */
    public static function successWithObject(): array
    {
        return self::getJson(__DIR__ . '/Responses/SuccessWithObject.json');
    }

    /**
     * @throws RocketException
     */
    public static function getUnsuccessfulWithException(): array
    {
        return self::getJson(__DIR__ . '/Responses/UnsuccessfulWithException.json');
    }

    /**
     * @throws RocketException
     */
    public static function getSuccessWithoutObject(): array
    {
        return self::getJson(__DIR__ . '/Responses/SuccessWithoutObject.json');
    }

    /**
     * @throws RocketException
     */
    public static function getCommandsGet(): array
    {
        return self::getJson(__DIR__ . '/Responses/Commands/Get.json');
    }

    /**
     * @throws RocketException
     */
    public static function getCommandsList(): array
    {
        return self::getJson(__DIR__ . '/Responses/Commands/List.json');
    }

    /**
     * @throws RocketException
     */
    public static function getRolesCreate(): array
    {
        return self::getJson(__DIR__ . '/Responses/Roles/Create.json');
    }

    /**
     * @throws RocketException
     */
    public static function getUsersCreate(): array
    {
        return self::getJson(__DIR__ . '/Responses/Users/Create.json');
    }

    /**
     * @throws RocketException
     */
    public static function getChannelsList(): array
    {
        return self::getJson(__DIR__ . '/Responses/Channels/List.json');
    }

    public static function getUsersCreateAsObject(): object
    {
        $json = file_get_contents(__DIR__ . '/Responses/Users/Create.json');

        return json_decode($json);
    }

    /**
     * @throws RocketException
     */
    public static function getUsersList(): array
    {
        return self::getJson(__DIR__ . '/Responses/Users/List.json');
    }

    /**
     * @throws RocketException
     */
    private static function getJson(string $path): array
    {
        $json = file_get_contents($path);

        if (! $json) {
            throw new RocketException('no valid json found');
        }

        return json_decode($json, true);
    }
}
