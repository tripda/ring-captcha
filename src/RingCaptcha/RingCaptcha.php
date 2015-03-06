<?php

namespace RingCaptcha;

use Guzzle\Http\Client as GuzzleClient;
use Guzzle\Http\Message\Response;
use RingCaptcha\Constants\ErrorResponse;
use RingCaptcha\Constants\MessageResponse;

Class RingCaptcha
{
    const BASE_URL = 'https://api.ringcaptcha.com/%s/%s';

    private $apiKey;
    private $appKey;
    private $http;

    public function __construct($apiKey, $appKey, $client = null)
    {
        $this->apiKey = $apiKey;
        $this->appKey = $appKey;
        $this->http = $this->configureHttpClient($client);
    }

    private function prepareUrl($route)
    {
       return sprintf(self::BASE_URL,$this->appKey,$route);
    }

    private function prepareUrlSendCode()
    {
        return $this->prepareUrl('code/sms');
    }

    private function prepareUrlVerifyCode()
    {
        return $this->prepareUrl('verify');
    }

    private function prepareUrlSendSMS()
    {
        return $this->prepareUrl('sms');
    }

    private function getDefaultParams()
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

    protected function prepareResponse(Response $data)
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

    protected function executeQuery(array $params, $url)
    {
        $data = $this->http->post($url,$params)->send();

        return $this->prepareResponse($data);
    }

    public function sendVerificationPinCode($internationalNumber)
    {
        if (!isset($internationalNumber)) {
            throw new \InvalidArgumentException(
                ErrorResponse::getErrorMessage(ErrorResponse::PHONE_NUMBER_NOT_DEFINED)
            );
        }

        $params = $this->getDefaultParams();
        $params['body']['phone'] = $internationalNumber;

        $url = $this->prepareUrlSendCode();

        return $this->executeQuery($params, $url);
    }

    public function verifyPinCode($internationalNumber, $pinCode)
    {
        if (!isset($internationalNumber) || !isset($pinCode)) {
            throw new \InvalidArgumentException(
                ErrorResponse::getErrorMessage(ErrorResponse::PHONE_OR_PIN_NOT_DEFINED)
            );
        }

        $params = $this->getDefaultParams();
        $params['body']['phone'] = $internationalNumber;
        $params['body']['code'] = $pinCode;

        $url = $this->prepareUrlVerifyCode();

        return $this->executeQuery($params, $url);
    }

    public function sendSMS($internationalNumber, $message)
    {
        if (!isset($internationalNumber) || !isset($message)) {
            throw new \InvalidArgumentException(
                ErrorResponse::getErrorMessage(ErrorResponse::PHONE_OR_MESSAGE_NOT_DEFINED)
            );
        }

        $params = $this->getDefaultParams();
        $params['body']['phone'] = $internationalNumber;
        $params['body']['message'] = $message;

        $url = $this->prepareUrlSendSMS();

        return $this->executeQuery($params, $url);
    }

    private static function configureHttpClient($client)
    {
        if ($client == null) {
            return new GuzzleClient();
        }

        return $client;
    }
}