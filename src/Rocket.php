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

final class Rocket
{
    private static Rocket $instance;

    private string $url;
    private string $userId;
    private string $authToken;
    private string $password;
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

        if (config('rocket.user.password')) {
            $this->password = config('rocket.user.password');
        }
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
            self::$instance = new self();
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

        foreach ($data as $key => $value) {
            if (gettype($value) == 'string' && empty($value)) {
                throw new RocketException("Argument '$key' in Endpoint '$endpoint' cant be empty. Failed to send post request");
            }
        }

        $url = $this->url . '/api/v1/' . $endpoint;
        $headers = $this->headers;
        $headers['Content-Type'] = 'application/json';

        $response = Http::timeout($this->timeout)->retry($this->retries, $this->sleep)->withHeaders($headers)->post($url, $data);

        if ($response->failed()) {
            throw new RocketException("Request failed with Code: {$response->status()}");
        }

        $this->checkResponse($response->object());

        return $response->object();
    }

    /**
     * @throws RocketException
     */
    public function get(string $endpoint, ?array $query = null): object
    {
        $url = $this->url . '/api/v1/' . $endpoint;

        if ($query) {
            foreach ($query as $key => $value) {
                if (gettype($value) == 'string' && empty($value)) {
                    throw new RocketException("Argument '$key' in Endpoint '$endpoint' cant be empty. Failed to send get request");
                }
            }
        }

        $response = Http::timeout($this->timeout)->retry($this->retries, $this->sleep)->withHeaders($this->headers)->get($url, $query);

        if ($response->failed()) {
            throw new RocketException("Request failed with Code: {$response->status()}");
        }

        $this->checkResponse($response->object());

        return $response->object();
    }

    /**
     * @throws RocketException
     */
    public function postWith2FA(string $endpoint, array $data): object
    {
        if (empty($this->password)) {
            throw new RocketException('Password required for 2FA requests. Please set it in your Laravel .env file');
        }

        foreach ($data as $key => $value) {
            if (gettype($value) == 'string' && empty($value)) {
                throw new RocketException("Argument '$key' in Endpoint '$endpoint' cant be empty. Failed to send postWith2FA request");
            }
        }

        $url = $this->url . '/api/v1/' . $endpoint;
        $headers = $this->headers;
        $headers['Content-Type'] = 'application/json';
        $headers['x-2fa-code'] = hash('sha256', $this->password);
        $headers['x-2fa-method'] = 'password';

        $response = Http::timeout($this->timeout)->retry($this->retries, $this->sleep)->withHeaders($headers)->post($url, $data);

        if ($response->failed()) {
            throw new RocketException("Request failed with Code: {$response->status()}");
        }

        $this->checkResponse($response->object());

        return $response->object();
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
            if (isset($response->error) && isset($response->errorType)) {
                throw new RocketException("Request wasn't successful. Reason: '$response->error'", $response->errorType);
            }

            throw new RocketException("Request wasn't successful");
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
