<?php

namespace RingCaptcha;

use Guzzle\Http\Client as GuzzleClient;
use Guzzle\Http\Message\Response;
use RingCaptcha\Constant\MessageResponse;
use RingCaptcha\Exception\MessageNotDefined;
use RingCaptcha\Exception\PhoneNumberNotDefined;
use RingCaptcha\Exception\PinCodeNotDefined;
use RingCaptcha\Exception\RequestException;

Class RingCaptcha
{
    const BASE_URL = 'https://api.ringcaptcha.com/%s/%s';

    private $apiKey;
    private $appKey;
    private $http;
    private $client;

    public function __construct($apiKey, $appKey)
    {
        $this->apiKey = $apiKey;
        $this->appKey = $appKey;
    }

    public function setClient(GuzzleClient $client)
    {
        $this->client = $client;
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
                'api_key' => $this->apiKey,
        ];

        return $params;
    }

    protected function validateResponse(array $response)
    {
        if (isset($response['status']) && $response['status']==='ERROR') {
            throw new RequestException(
                MessageResponse::getMessageResponse($response['message']),
                json_encode($response)
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
        $this->http = $this->configureHttpClient($this->client);

        $data = $this->http->post(
            $url, array('Content-Type' => 'application/x-www-url-encoded; charset=utf-8'), $params)->send();

        return $this->prepareResponse($data);
    }

    public function sendVerificationPinCode($internationalNumber)
    {
        if (!isset($internationalNumber)) {
            throw new PhoneNumberNotDefined();
        }

        $params = $this->getDefaultParams();
        $params['phone'] = $internationalNumber;

        $url = $this->prepareUrlSendCode();

        return $this->executeQuery($params, $url);
    }

    public function verifyPinCode($internationalNumber, $pinCode)
    {
        if (!isset($internationalNumber)) {
            throw new PhoneNumberNotDefined();
        }

        if (!isset($pinCode)) {
            throw new PinCodeNotDefined();
        }

        $params = $this->getDefaultParams();
        $params['phone'] = $internationalNumber;
        $params['code'] = $pinCode;

        $url = $this->prepareUrlVerifyCode();

        return $this->executeQuery($params, $url);
    }

    public function sendSMS($internationalNumber, $message)
    {
        if (!isset($internationalNumber)) {
            throw new PhoneNumberNotDefined();
        }

        if (!isset($message)) {
            throw new MessageNotDefined();
        }

        $params = $this->getDefaultParams();
        $params['phone'] = $internationalNumber;
        $params['message'] = $message;

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