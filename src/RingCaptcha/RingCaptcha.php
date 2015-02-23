<?php

namespace RingCaptcha;

use GuzzleHttp\Client;
use GuzzleHttp\Message\ResponseInterface;
use RingCaptcha\Constants\ErrorResponse;
use RingCaptcha\Constants\MessageResponse;

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
        if (!isset($apiKey) || !isset($appKey)) {
            throw new \InvalidArgumentException(
                ErrorResponse::getErrorMessage(ErrorResponse::KEYS_NOT_DEFINED)
            );
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

    protected function validateResponse(array $response)
    {
        if (isset($response['status']) && $response['status']==='ERROR') {
            throw new \InvalidArgumentException(
                MessageResponse::getMessageResponse($response['message'])
            );
        }

        return true;
    }

    protected function prepareResponse(ResponseInterface $data)
    {
        if ($data->getStatusCode()!=200) {
            throw new \InvalidArgumentException(
                $data->getReasonPhrase()
            );
        }

        $response = json_decode($data->getBody(), true);
        $this->validateResponse($response);

        return $response;
    }

    protected function executeQuery(array $params, $service)
    {
        $formattedUrl = sprintf(self::BASE_URL,$this->appKey,self::$services[$service]);
        $data = $this->http->post($formattedUrl,$params);

        return $this->prepareResponse($data);
    }

    public function sendVerificationPinCode($phoneNumber)
    {
        if (!isset($phoneNumber)) {
            throw new \InvalidArgumentException(
                ErrorResponse::getErrorMessage(ErrorResponse::PHONE_NUMBER_NOT_DEFINED)
            );
        }

        $params = $this->getDefaultParams();
        $params['body']['phone'] = $phoneNumber;

        return $this->executeQuery($params, 0);
    }

    public function verifyPinCode($phoneNumber, $pinCode)
    {
        if (!isset($phoneNumber) || !isset($pinCode)) {
            throw new \InvalidArgumentException(
                ErrorResponse::getErrorMessage(ErrorResponse::PHONE_OR_PIN_NOT_DEFINED)
            );
        }

        $params = $this->getDefaultParams();
        $params['body']['phone'] = $phoneNumber;
        $params['body']['code'] = $pinCode;

        return $this->executeQuery($params, 1);
    }

    public function sendSMS($phoneNumber, $message)
    {
        if (!isset($phoneNumber) || !isset($message)) {
            throw new \InvalidArgumentException(
                ErrorResponse::getErrorMessage(ErrorResponse::PHONE_OR_MESSAGE_NOT_DEFINED)
            );
        }

        $params = $this->getDefaultParams();
        $params['body']['phone'] = $phoneNumber;
        $params['body']['message'] = $message;

        return $this->executeQuery($params, 2);
    }
}