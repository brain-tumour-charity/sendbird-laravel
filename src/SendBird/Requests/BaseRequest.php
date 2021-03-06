<?php

namespace SendBird\Requests;

use GuzzleHttp\Client;
use Illuminate\Support\Str;

abstract class BaseRequest
{
    protected $app_id;
    protected $base_url;

    private $httpOptions;

    public function __construct()
    {
        $this->app_id = config('sendbird.app_id');

        $this->base_url = "https://api-{$this->app_id}.sendbird.com/v3";

        $this->configureHttpOptions();
    }

    private function configureHttpOptions()
    {
        $this->httpOptions = [
            'http_errors' => false,
            'headers' => [
                'Content-Type' => "application/json, charset=utf8",
                'Api-Token' => config('sendbird.master_token'),
                'Accept' => "application/json"
            ]
        ];
    }

    protected function request($endpoint, $method = 'post', $body = [])
    {
        $url = $this->base_url . Str::start($endpoint, '/');

        $httpOptions = $this->httpOptions;

        if (strtolower($method) !== 'get') {
            $httpOptions['body'] = json_encode($body);
        }

        $http = new Client();

        $response = $http->request($method, $url, $httpOptions);
        $body = json_decode($response->getBody(), true);

        if (isset($body['error'])) {
            throw new \Exception($body['message'], $body['code']);
        }

        return $body;
    }
}
