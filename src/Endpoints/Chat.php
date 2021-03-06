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
     * prefix # in channel refers to channel
     * prefix @ in channel refers to username
     */
    public function postChannelMessage(string $channel, string $text): Message
    {
        $this->checkEmptyString($channel);
        $this->checkEmptyString($text);

        $data = get_defined_vars();
        $response = $this->rocket->post("chat.postMessage", $data);

        return Deserializer::deserialize($response, Message::class);
    }

    /**
     * @throws RocketException
     */
    public function postRoomMessage(string $roomId, string $text): Message
    {
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
        $this
            ->checkEmptyString($roomId)
            ->checkEmptyString($msgId);

        $data = get_defined_vars();

        try {
            $this->rocket->post("chat.delete", $data);
        } catch (RocketException $re) {
            if (str_starts_with($re->getMessage(), 'No message found with the id of')) {
                return;
            }

            throw $re;
        }
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
    public function getMessage(string $msgId): ?Message
    {
        $this->checkEmptyString($msgId);
        $query = get_defined_vars();

        try {
            $response = $this->rocket->get("chat.getMessage", $query);
        } catch (RocketException $re) {
            if ($re->getMessage() === '{"success":false}') {
                return null;
            }

            throw $re;
        }

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
        $this
            ->checkEmptyString($messageId)
            ->checkEmptyString($emoji);

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
