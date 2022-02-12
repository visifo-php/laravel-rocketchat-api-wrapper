<?php

declare(strict_types=1);

namespace visifo\Rocket;

use Exception;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
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
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @throws RocketException
     */
    public function get(string $endpoint, ?array $query = null): Response
    {
        $url = $this->url . '/api/v1/' . $endpoint;

        $this->logRequest('get', $url, $query);

        $response = Http::timeout($this->timeout)->retry($this->retries, $this->sleep)->withHeaders($this->headers)->get($url, $query);

        $this->logResponse($response);
        $this->checkResponse($response);

        return $response;
    }

    /**
     * @throws RocketException
     */
    public function post(string $endpoint, array $data): Response
    {
        if (empty($data)) {
            throw new RocketException('post data array cant be empty');
        }

        $url = $this->url . '/api/v1/' . $endpoint;
        $headers = $this->headers;
        $headers['Content-Type'] = 'application/json';

        $this->logRequest('post', $url, $data);

        $response = Http::timeout($this->timeout)->retry($this->retries, $this->sleep)->withHeaders($headers)->post($url, $data);

        $this->logResponse($response);
        $this->checkResponse($response);

        return $response;
    }

    /**
     * @throws RocketException
     */
    public function postWith2FA(string $endpoint, array $data): Response
    {
        if (empty($this->password)) {
            throw new RocketException('Password required for 2FA requests. Please set it in your Laravel .env file');
        }

        $url = $this->url . '/api/v1/' . $endpoint;
        $headers = $this->headers;
        $headers['Content-Type'] = 'application/json';
        $headers['x-2fa-code'] = hash('sha256', $this->password);
        $headers['x-2fa-method'] = 'password';

        $this->logRequest('postWith2FA', $url, $data);

        $response = Http::timeout($this->timeout)->retry($this->retries, $this->sleep)->withHeaders($headers)->post($url, $data);

        $this->logResponse($response);
        $this->checkResponse($response);

        return $response;
    }

    public function logRequest(string $method, string $url, ?array $data): void
    {
        if (config('rocket.debug_logs_enabled')) {
            Log::debug("Request: $method $url " . json_encode($data));
        }
    }

    public function logResponse(Response $response): void
    {
        if (config('rocket.debug_logs_enabled')) {
            Log::debug("Response: {$response->status()} {$response->body()}");
        }
    }

    /**
     * @throws RocketException
     */
    public function checkResponse(Response $response)
    {
        $responseObject = $response->object();

        if ($response->failed()) {
            throw new RocketException(
                $responseObject?->error ?? $response->body(),
                $response->status(),
                errorType: $responseObject?->errorType ?? null
            );
        }

        if (!isset($responseObject->success)) {
            throw new RocketException("Property: 'success' must be set in RocketChat response");
        }

        if (!$responseObject->success) {
            throw new RocketException(
                "Request wasn't successful. Reason: '" . ($responseObject?->error ?? '') . "'",
                errorType: $responseObject?->errorType ?? null
            );
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
