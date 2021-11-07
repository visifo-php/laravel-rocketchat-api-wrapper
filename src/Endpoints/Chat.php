<?php

declare(strict_types=1);

namespace visifo\Rocket\Endpoints;

use visifo\Rocket\Deserializer;
use visifo\Rocket\Objects\Chat\Message;
use visifo\Rocket\Rocket;
use visifo\Rocket\RocketException;

class Chat extends Endpoint
{
    private Rocket $rocket;

    public function __construct(Rocket $rocket)
    {
        $this->rocket = $rocket;
    }

    /**
     * @throws RocketException
     */
    public function postMessage(string $roomId, string $text, ?string $channel = null): Message
    {
        if (empty($channel)) {
            unset($channel);
        }

        $this->checkEmptyString($roomId);
        $this->checkEmptyString($text);
        $data = get_defined_vars();
        $response = $this->rocket->post("chat.postMessage", $data);

        return Deserializer::deserialize($response, Message::class);
    }

    /**
     * @throws RocketException
     */
    public function delete(string $roomId, string $msgId, bool $asUser = false): void
    {
        $this->checkEmptyString($roomId);
        $this->checkEmptyString($msgId);
        $data = get_defined_vars();
        $this->rocket->post("chat.delete", $data);
    }

    /**
     * @throws RocketException
     */
    public function followMessage(string $mid): void
    {
        $this->checkEmptyString($mid);
        $data = get_defined_vars();
        $this->rocket->post("chat.followMessage", $data);
    }

    /**
     * @throws RocketException
     */
    public function getMessage(string $msgId): Message
    {
        $this->checkEmptyString($msgId);
        $query = get_defined_vars();
        $response = $this->rocket->get("chat.getMessage", $query);

        return Deserializer::deserialize($response, Message::class);
    }

    /**
     * @throws RocketException
     */
    public function pinMessage(string $messageId): void
    {
        $this->checkEmptyString($messageId);
        $data = get_defined_vars();
        $this->rocket->post("chat.pinMessage", $data);
    }

    /**
     * @throws RocketException
     */
    public function react(string $messageId, string $emoji): void
    {
        $this->checkEmptyString($messageId);
        $this->checkEmptyString($emoji);
        $data = get_defined_vars();
        $this->rocket->post("chat.react", $data);
    }

    /**
     * @throws RocketException
     */
    public function starMessage(string $messageId): void
    {
        $this->checkEmptyString($messageId);
        $data = get_defined_vars();
        $this->rocket->post("chat.starMessage", $data);
    }

    /**
     * @throws RocketException
     */
    public function unfollowMessage(string $mid): void
    {
        $this->checkEmptyString($mid);
        $data = get_defined_vars();
        $this->rocket->post("chat.unfollowMessage", $data);
    }

    /**
     * @throws RocketException
     */
    public function unPinMessage(string $messageId): void
    {
        $this->checkEmptyString($messageId);
        $data = get_defined_vars();
        $this->rocket->post("chat.unPinMessage", $data);
    }
}