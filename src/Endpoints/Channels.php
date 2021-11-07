<?php
declare(strict_types=1);

namespace visifo\Rocket\Endpoints;

use visifo\Rocket\Objects\Channels\Channel;
use visifo\Rocket\Deserializer;
use visifo\Rocket\Rocket;
use visifo\Rocket\RocketException;

class Channels extends Endpoint
{
    private Rocket $rocket;

    public function __construct(Rocket $rocket)
    {
        $this->rocket = $rocket;
    }

    /**
     * @throws RocketException
     */
    public function create(string $name, bool $readOnly = false, array $members = []): Channel
    {
        $this->checkEmptyString($name);
        $data = get_defined_vars();
        $response = $this->rocket->post("channels.create", $data);
        return Deserializer::deserialize($response, Channel::class);
    }

    /**
     * @throws RocketException
     */
    public function info(string $roomId = "", string $roomName = ""): Channel
    {
        if ($roomId) {
            $this->checkEmptyString($roomId);
            $query['roomId'] = $roomId;
        } else if ($roomName) {
            $this->checkEmptyString($roomName);
            $query['roomName'] = $roomName;
        } else
            throw new RocketException("roomId or roomName must be set to get Channel Info");

        $response = $this->rocket->get("channels.info", $query);
        return Deserializer::deserialize($response, Channel::class);
    }

    /**
     * @throws RocketException
     */
    public function delete(string $roomId = "", string $roomName = ""): void
    {
        if ($roomId) {
            $this->checkEmptyString($roomId);
            $data['roomId'] = $roomId;
        } else if ($roomName) {
            $this->checkEmptyString($roomName);
            $data['roomName'] = $roomName;
        } else
            throw new RocketException("roomId or roomName must be set to delete a Channel");

        $this->rocket->post("channels.delete", $data);
    }

    /**
     * @throws RocketException
     */
    public function setTopic(string $roomId, string $topic): void
    {
        $this->checkEmptyString($roomId);
        $this->checkEmptyString($topic);
        $data = get_defined_vars();
        $this->rocket->post("channels.setTopic", $data);
    }

    /**
     * @throws RocketException
     */
    public function addAll(string $roomId, bool $activeUsersOnly = false): void
    {
        $this->checkEmptyString($roomId);
        $data = get_defined_vars();
        $this->rocket->post("channels.addAll", $data);
    }

    /**
     * @throws RocketException
     */
    public function addLeader(string $roomId, string $userId): void
    {
        $this->checkEmptyString($roomId);
        $this->checkEmptyString($userId);
        $data = get_defined_vars();
        $this->rocket->post("channels.addLeader", $data);
    }

    /**
     * @throws RocketException
     */
    public function addModerator(string $roomId, string $userId): void
    {
        $this->checkEmptyString($roomId);
        $this->checkEmptyString($userId);
        $data = get_defined_vars();
        $this->rocket->post("channels.addModerator", $data);
    }

    /**
     * @throws RocketException
     */
    public function addOwner(string $roomId, string $userId): void
    {
        $this->checkEmptyString($roomId);
        $this->checkEmptyString($userId);
        $data = get_defined_vars();
        $this->rocket->post("channels.addOwner", $data);
    }

    /**
     * @throws RocketException
     */
    public function archive(string $roomId): void
    {
        $this->checkEmptyString($roomId);
        $data = get_defined_vars();
        $this->rocket->post("channels.archive", $data);
    }

    /**
     * @throws RocketException
     */
    public function close(string $roomId): void
    {
        $this->checkEmptyString($roomId);
        $data = get_defined_vars();
        $this->rocket->post("channels.close", $data);
    }

    /**
     * @throws RocketException
     */
    public function invite(string $roomId, string $userId): void
    {
        if ($roomId) {
            $this->checkEmptyString($roomId);
            $data['roomId'] = $roomId;

        } else if ($userId) {
            $this->checkEmptyString($userId);
            $data['userId'] = $userId;

        } else
            throw new RocketException("roomId or userId must be set to invite a User to a Channel");

        $data = get_defined_vars();
        $this->rocket->post("channels.invite", $data);
    }

    /**
     * @throws RocketException
     */
    public function join(string $roomId): void
    {
        $this->checkEmptyString($roomId);
        $data = get_defined_vars();
        $this->rocket->post("channels.join", $data);
    }

    /**
     * @throws RocketException
     */
    public function kick(string $roomId, string $userId): void
    {
        $this->checkEmptyString($roomId);
        $this->checkEmptyString($userId);
        $data = get_defined_vars();
        $this->rocket->post("channels.kick", $data);
    }

    /**
     * @throws RocketException
     */
    public function leave(string $roomId): void
    {
        $this->checkEmptyString($roomId);
        $data = get_defined_vars();
        $this->rocket->post("channels.leave", $data);
    }

    /**
     * @throws RocketException
     */
    public function open(string $roomId): void
    {
        $this->checkEmptyString($roomId);
        $data = get_defined_vars();
        $this->rocket->post("channels.open", $data);
    }

    /**
     * @throws RocketException
     */
    public function removeLeader(string $roomId, string $userId): void
    {
        $this->checkEmptyString($roomId);
        $this->checkEmptyString($userId);
        $data = get_defined_vars();
        $this->rocket->post("channels.removeLeader", $data);
    }

    /**
     * @throws RocketException
     */
    public function removeModerator(string $roomId, string $userId): void
    {
        $this->checkEmptyString($roomId);
        $this->checkEmptyString($userId);
        $data = get_defined_vars();
        $this->rocket->post("channels.removeModerator", $data);
    }

    /**
     * @throws RocketException
     */
    public function removeOwner(string $roomId, string $userId): void
    {
        $this->checkEmptyString($roomId);
        $this->checkEmptyString($userId);
        $data = get_defined_vars();
        $this->rocket->post("channels.removeOwner", $data);
    }

    /**
     * @throws RocketException
     */
    public function rename(string $roomId, string $name): void
    {
        $this->checkEmptyString($roomId);
        $this->checkEmptyString($name);
        $data = get_defined_vars();
        $this->rocket->post("channels.rename", $data);
    }

    /**
     * @throws RocketException
     */
    public function setAnnouncement(string $roomId, string $announcement): void
    {
        $this->checkEmptyString($roomId);
        $this->checkEmptyString($announcement);
        $data = get_defined_vars();
        $this->rocket->post("channels.setAnnouncement", $data);
    }

    /**
     * @throws RocketException
     */
    public function setDefault(string $roomId, string $default): void
    {
        $this->checkEmptyString($roomId);
        $this->checkEmptyString($default);
        $data = get_defined_vars();
        $this->rocket->post("channels.setDefault", $data);
    }

    /**
     * @throws RocketException
     */
    public function setDescription(string $roomId, string $description): void
    {
        $this->checkEmptyString($roomId);
        $this->checkEmptyString($description);
        $data = get_defined_vars();
        $this->rocket->post("channels.setDescription", $data);
    }

    /**
     * @throws RocketException
     */
    public function setJoinCode(string $roomId, string $joinCode): void
    {
        $this->checkEmptyString($roomId);
        $this->checkEmptyString($joinCode);
        $data = get_defined_vars();
        $this->rocket->post("channels.setJoinCode", $data);
    }

    /**
     * @throws RocketException
     */
    public function setPurpose(string $roomId, string $purpose): void
    {
        $this->checkEmptyString($roomId);
        $this->checkEmptyString($purpose);
        $data = get_defined_vars();
        $this->rocket->post("channels.setPurpose", $data);
    }

    /**
     * @throws RocketException
     */
    public function setReadOnly(string $roomId, bool $readOnly): void
    {
        $this->checkEmptyString($roomId);
        $data = get_defined_vars();
        $this->rocket->post("channels.setReadOnly", $data);
    }

    /**
     * @throws RocketException
     * type is either "c" for channel or "p" for private
     */
    public function setType(string $type, string $roomId = "", string $roomName = ""): void
    {
        $this->checkEmptyString($type);

        if ($roomId) {
            $this->checkEmptyString($roomId);
            $data['roomId'] = $roomId;

        } else if ($roomName) {
            $this->checkEmptyString($roomName);
            $data['roomName'] = $roomName;
        } else
            throw new RocketException("type + roomId or roomName must be set to delete a Channel");

        $data['type'] = $type;

        $this->rocket->post("channels.setType", $data);
    }

    /**
     * @throws RocketException
     */
    public function unarchive(string $roomId): void
    {
        $this->checkEmptyString($roomId);
        $data = get_defined_vars();
        $this->rocket->post("channels.unarchive", $data);
    }
}