<?php

declare(strict_types=1);

namespace visifo\Rocket;

use Illuminate\Support\Facades\Http;
use visifo\Rocket\Endpoints\Channels;
use visifo\Rocket\Endpoints\Chat;
use visifo\Rocket\Endpoints\Commands;
use visifo\Rocket\Endpoints\Roles;
use visifo\Rocket\Endpoints\Users;

class Rocket
{
    private static Rocket $instance;

    public string $url;
    public string $userId;
    public string $authToken;
    public string $userName;
    public string $userPassword;
    public array $headers;

    public Channels $channels;
    public Chat $chat;
    public Commands $commands;
    public Roles $roles;
    public Users $users;

    private function __construct()
    {
        $this->url = config('rocket.url');
        $this->userId = config('rocket.user.id');
        $this->authToken = config('rocket.authToken');
        $this->userName = config('rocket.user.name');
        $this->userPassword = config('rocket.user.password');
        $this->headers = [
            'X-User-Id' => $this->userId,
            'X-Auth-Token' => $this->authToken,
            'Accept' => 'application/json',
        ];

        $this->channels = new Channels($this);
        $this->chat = new Chat($this);
        $this->commands = new Commands($this);
        $this->roles = new Roles($this);
        $this->users = new Users($this);
    }

    private function __clone()
    {
    }

    public static function getInstance(): Rocket
    {
        if (! isset(self::$instance)) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    /**
     * @throws RocketException
     */
    public function post(string $endpoint, array $data): object
    {
        if (empty($data)) {
            throw new RocketException('post data array cant be empty');
        }

        $url = $this->url . '/api/v1/' . $endpoint;
        $headers = $this->headers;
        $headers['Content-Type'] = 'application/json';
        $response = Http::timeout(2)->retry(2, 1000)->withHeaders($headers)->post($url, $data)->object()
            ?? throw new RocketException();

        $this->checkResponse($response);

        return $response;
    }

    /**
     * @throws RocketException
     */
    public function get(string $endpoint, ?array $query = null): object
    {
        $url = $this->url . '/api/v1/' . $endpoint;
        $response = Http::timeout(2)->retry(2, 1000)->withHeaders($this->headers)->get($url, $query)->object()
            ?? throw new RocketException("Failed to receive response from RocketChat");

        $this->checkResponse($response);

        return $response;
    }

    public function securePost()
    {
    }

    /**
     * @throws RocketException
     */
    public function checkResponse(object $response)
    {
        if (! isset($response->success)) {
            throw new RocketException("Property: 'success' must be set in RocketChat response");
        }

        if (! $response->success) {
            throw new RocketException("Request wasn't successful. Reason: '$response->error'", $response->errorType);
        }
    }
}
