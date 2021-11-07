<?php

namespace visifo\Rocket\Objects\Chat;

use visifo\Rocket\Objects\Common\User;

class Message
{
    /** @replace _id */
    public string $id;
    /** @replace msg */
    public string $message;
    /** @replace rid */
    public string $channelId;
    /** @replace u */
    public User $user;
}
