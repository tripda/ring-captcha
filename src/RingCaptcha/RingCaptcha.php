<?php

namespace RingCaptcha;

use GuzzleHttp\Client;
use GuzzleHttp\Message\ResponseInterface;

Class RingCaptcha
{
    const BASE_URL = 'https://api.ringcaptcha.com/%s/%s';
    protected $apiKey;
    protected $appKey;
    protected $http;

    protected static $services = [
        0 => 'code/sms',
        1 => 'verify',
        2 => 'sms'
    ];

    public function __construct($apiKey, $appKey)
    {
        if (!$apiKey || !$appKey)
        {
            throw new \InvalidArgumentException('Api key or appkey is not defined');
        }

        $this->apiKey = $apiKey;
        $this->appKey = $appKey;
        $this->http = new Client();
    }

    protected function getDefaultParams()
    {
        $params = [
            'body' => [
                'api_key' => $this->apiKey,
            ]
        ];

        return $params;
    }

    protected function prepareResponse(ResponseInterface $data)
    {
        if ($data->getStatusCode()!=200)
        {
            return [];
        }

        return json_decode($data->getBody(), true);
    }

    protected function executeQuery(array $params, $service)
    {
        $formattedUrl = sprintf(self::BASE_URL,$this->appKey,self::$services[$service]);
        $response = $this->http->post($formattedUrl,$params);
        
        return $this->prepareResponse($response);
    }

    public function sendVerificationPinCode($phoneNumber)
    {
        if (!$phoneNumber)
        {
            throw new \InvalidArgumentException('Phone number is empty');
        }

        $params = $this->getDefaultParams();
        $params['body']['phone'] = $phoneNumber;

        return $this->executeQuery($params, 0);
    }

    public function verifyPinCode($phoneNumber, $pinCode)
    {
        if (!$phoneNumber || !$pinCode)
        {
            throw new \InvalidArgumentException('Phone number or pin code is empty');
        }

        $params = $this->getDefaultParams();
        $params['body']['phone'] = $phoneNumber;
        $params['body']['code'] = $pinCode;

        return $this->executeQuery($params, 1);
    }

    public function sendSMS($phoneNumber, $message)
    {
        if (!$phoneNumber || !$message)
        {
            throw new \InvalidArgumentException('Phone number or message is empty');
        }

        $params = $this->getDefaultParams();
        $params['body']['phone'] = $phoneNumber;
        $params['body']['message'] = $message;

        return $this->executeQuery($params, 2);
    }
}