<?php

namespace RingCaptcha;

use Guzzle\Http\Message\Response;
use RingCaptcha\Constants\ErrorResponse;
use RingCaptcha\Constants\MessageResponse;
use RingCaptcha\Model\ConfigurationModel;

Class RingCaptcha
{
    const BASE_URL = 'https://api.ringcaptcha.com/%s/%s';

    private $apiKey;
    private $appKey;
    private $http;

    public function __construct(ConfigurationModel $configuration)
    {
        $this->apiKey = $configuration->getApiKey();
        $this->appKey = $configuration->getAppKey();
        $this->http = $configuration->getHttpClient();
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

    public function sendVerificationPinCode($phoneNumber)
    {
        if (!isset($phoneNumber)) {
            throw new \InvalidArgumentException(
                ErrorResponse::getErrorMessage(ErrorResponse::PHONE_NUMBER_NOT_DEFINED)
            );
        }

        $params = $this->getDefaultParams();
        $params['body']['phone'] = $phoneNumber;

        $url = $this->prepareUrlSendCode();

        return $this->executeQuery($params, $url);
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

        $url = $this->prepareUrlVerifyCode();

        return $this->executeQuery($params, $url);
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

        $url = $this->prepareUrlSendSMS();

        return $this->executeQuery($params, $url);
    }
}