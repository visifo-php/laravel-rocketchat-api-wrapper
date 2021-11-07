<?php

namespace visifo\Rocket\Objects\Channels;

use visifo\Rocket\Objects\Common\User;

class Channel
{
    /** @replace _id */
    public string $id;
    public string $name;
    /** @replace t */
    public string $type;
    /** @replace msgs */
    public int $messageCount;
    /** @replace u */
    public User $user;
}
