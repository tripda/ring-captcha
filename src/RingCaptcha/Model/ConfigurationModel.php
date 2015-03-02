<?php

namespace RingCaptcha\Model;

use Guzzle\Http\Client as GuzzleClient;
use RingCaptcha\Constants\ErrorResponse;

class ConfigurationModel
{
    private $apiKey;
    private $appKey;
    private $httpClient;

    public function __construct($apiKey, $appKey, GuzzleClient $client)
    {
        if (!isset($apiKey) || !isset($appKey)) {
            throw new \InvalidArgumentException(
                ErrorResponse::getErrorMessage(ErrorResponse::KEYS_NOT_DEFINED)
            );
        }

        $this->apiKey = $apiKey;
        $this->appKey = $appKey;
        $this->httpClient = $client;
    }

    public function getApiKey()
    {
        return $this->apiKey;
    }

    public function getAppKey()
    {
        return $this->appKey;
    }

    public function getHttpClient()
    {
        return $this->httpClient;
    }
}
