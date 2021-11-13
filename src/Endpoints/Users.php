<?php

declare(strict_types=1);

namespace visifo\Rocket\Endpoints;

use visifo\Rocket\Deserializer;
use visifo\Rocket\Objects\Users\User;
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
    public function create(string $name, bool $readOnly = false, array $members = []): User
    {
        $this->checkEmptyString($name);
        $data = get_defined_vars();
        $response = $this->rocket->post("users.create", $data);

        return Deserializer::deserialize($response, User::class);
    }

    /**
     * @throws RocketException
     */
    public function delete(string $userId): void
    {
        $this->checkEmptyString($userId);
        $data = get_defined_vars();
        $this->rocket->post("users.delete", $data);
    }

    /**
     * @throws RocketException
     */
    public function register(string $username, string $email, string $pass, string $name): void
    {
        $this->checkEmptyString($username);
        $this->checkEmptyString($email);
        $this->checkEmptyString($pass);
        $this->checkEmptyString($name);

        $data = get_defined_vars();
        $this->rocket->post("users.register", $data);
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

        if (empty($data))
            throw new RocketException('data cant be empty');

        $data = get_defined_vars();
        $this->rocket->post("users.update", $data);
    }
}
