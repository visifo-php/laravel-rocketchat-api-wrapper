<?php

namespace visifo\Rocket\Objects\Users;

class UserCreate
{
    public string $email;
    public string $username;
    public string $name;
    public string $password;
    public bool $active;
    public array $roles;
    public bool $joinDefaultChannels;
    public bool $requirePasswordChange;
    public bool $sendWelcomeEmail;
    public bool $verified;

    public function __construct(string $email, string $username, string $name, string $password)
    {
        $this->email = $email;
        $this->username = $username;
        $this->name = $name;
        $this->password = $password;
        $this->active = true;
        $this->roles = ['user'];
        $this->joinDefaultChannels = true;
        $this->requirePasswordChange = false;
        $this->sendWelcomeEmail = false;
        $this->verified = false;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;

        return $this;
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function setJoinDefaultChannels(bool $joinDefaultChannels): static
    {
        $this->joinDefaultChannels = $joinDefaultChannels;

        return $this;
    }

    public function setRequirePasswordChange(bool $requirePasswordChange): static
    {
        $this->requirePasswordChange = $requirePasswordChange;

        return $this;
    }

    public function setSendWelcomeEmail(bool $sendWelcomeEmail): static
    {
        $this->sendWelcomeEmail = $sendWelcomeEmail;

        return $this;
    }

    public function setVerified(bool $verified): static
    {
        $this->verified = $verified;

        return $this;
    }
}
