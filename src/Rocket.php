<?php

declare(strict_types=1);

namespace visifo\Rocket;

use Exception;
use Illuminate\Support\Facades\Http;
use visifo\Rocket\Endpoints\Channels;
use visifo\Rocket\Endpoints\Chat;
use visifo\Rocket\Endpoints\Commands;
use visifo\Rocket\Endpoints\Roles;
use visifo\Rocket\Endpoints\Users;

class Rocket
{
    private static Rocket $instance;

    private string $url;
    private string $userId;
    private string $authToken;
    private array $headers;
    private int $timeout;
    private int $retries;
    private int $sleep;

    private function __construct()
    {
        $this->url = config('rocket.url');
        $this->userId = config('rocket.user.id');
        $this->authToken = config('rocket.authToken');
        $this->headers = [
            'X-User-Id' => $this->userId,
            'X-Auth-Token' => $this->authToken,
            'Accept' => 'application/json',
        ];
        $this->timeout = config('rocket.timeout');
        $this->retries = config('rocket.retries');
        $this->sleep = config('rocket.sleep');
    }

    /**
     * @throws Exception
     */
    private function __clone()
    {
        throw new Exception('Singletons should not be cloneable');
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
        $response = Http::timeout($this->timeout)->retry($this->retries, $this->sleep)->withHeaders($headers)->post($url, $data)->object()
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
        $response = Http::timeout($this->timeout)->retry($this->retries, $this->sleep)->withHeaders($this->headers)->get($url, $query)->object()
            ?? throw new RocketException("Failed to receive response from RocketChat");

        $this->checkResponse($response);

        return $response;
    }

    // TODO
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

        // TODO
        if (! $response->success) {
            throw new RocketException("Request wasn't successful. Reason: '$response->error'", $response->errorType);
        }
    }

    public function channels(): Channels
    {
        return new Channels($this);
    }

    public function chat(): Chat
    {
        return new Chat($this);
    }

    public function commands(): Commands
    {
        return new Commands($this);
    }

    public function roles(): Roles
    {
        return new Roles($this);
    }

    public function users(): Users
    {
        return new Users($this);
    }
}
