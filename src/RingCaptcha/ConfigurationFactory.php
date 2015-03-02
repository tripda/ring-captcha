<?php

namespace RingCaptcha;

use RingCaptcha\Model\ConfigurationModel;
use Guzzle\Http\Client as GuzzleClient;

class ConfigurationFactory
{
    private static function configureHttpClient($client)
    {
        if ($client == null) {
            return new GuzzleClient();
        }

        return $client;
    }

    public function createRingCaptchaConfiguration($apiKey, $appKey, $client = null)
    {
        $httpClient = self::configureHttpClient($client);

        return new ConfigurationModel($apiKey, $appKey, $httpClient);
    }
}
