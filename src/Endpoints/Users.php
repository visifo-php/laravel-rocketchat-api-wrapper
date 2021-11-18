<?php

declare(strict_types=1);

namespace visifo\Rocket\Endpoints;

use visifo\Rocket\Deserializer;
use visifo\Rocket\Objects\Users\User;
use visifo\Rocket\Objects\Users\UserCreate;
use visifo\Rocket\Rocket;
use visifo\Rocket\RocketException;

class Users extends Endpoint
{
    private Rocket $rocket;

    public function __construct(Rocket $rocket)
    {
        $this->rocket = $rocket;
    }

    /**
     * @throws RocketException
     */
    public function create(string $email, string $username, string $name, string $password, bool $joinDefaultChannels = true, bool $verified = false, array $roles = ['user']): User
    {
        $this->checkEmptyString($email);
        $this->checkEmptyString($username);
        $this->checkEmptyString($name);
        $this->checkEmptyString($password);

        if (empty($roles)) {
            throw new RocketException('Roles array cant be empty');
        }

        $data = get_defined_vars();

        $response = $this->rocket->post("users.create", $data);

        return Deserializer::deserialize($response, User::class);
    }

    /**
     * @throws RocketException
     */
    public function delete(string $userId = '', string $username = '', bool $confirmRelinquish = false): void
    {
        if ($userId) {
            $data['userId'] = $userId;
        } elseif ($username) {
            $data['username'] = $username;
        } else {
            throw new RocketException('userId or username must be set for Users.delete');
        }

        $data['confirmRelinquish'] = $confirmRelinquish;

        $this->rocket->post("users.delete", $data);
    }

    /**
     * @throws RocketException
     */
    public function register(string $username, string $email, string $pass, string $name): void
    {
        $this
            ->checkEmptyString($username)
            ->checkEmptyString($email)
            ->checkEmptyString($pass)
            ->checkEmptyString($name);

        $data = get_defined_vars();
        $this->rocket->post("users.register", $data);
    }

    /**
     * @throws RocketException
     */
    public function info(string $userId = '', string $username = ''): User
    {
        if ($userId) {
            $query['userId'] = $userId;
        } elseif ($username) {
            $query['username'] = $username;
        } else {
            throw new RocketException("userId or username must be set to get Users Info");
        }

        $response = $this->rocket->get("users.info", $query);

        return Deserializer::deserialize($response, User::class);
    }

    /**
     * @throws RocketException
     */
    public function setAvatar(string $avatarUrl, string $userId = '', string $username = ''): void
    {
        $this->checkEmptyString($avatarUrl);

        if ($userId) {
            $data['userId'] = $userId;
        } elseif ($username) {
            $data['username'] = $username;
        } else {
            throw new RocketException("userId or username must be set to get Users Info");
        }

        $data['avatarUrl'] = $avatarUrl;

        $this->rocket->get("users.setAvatar", $data);
    }

    /**
     * @throws RocketException
     */
    public function list(): \visifo\Rocket\Objects\Users\Users
    {
        $response = $this->rocket->get("users.list");

        return Deserializer::deserialize($response, \visifo\Rocket\Objects\Users\Users::class);
    }

    /**
     * @throws RocketException
     */
    public function update(string $userId, array $data): void
    {
        $this->checkEmptyString($userId);

        if (empty($data)) {
            throw new RocketException('data cant be empty');
        }

        $data = get_defined_vars();
        $this->rocket->postWith2FA("users.update", $data);
    }
}
