<?php

namespace Test\RingCaptcha;

use RingCaptcha\RingCaptcha;
use Guzzle\Http\Client as GuzzleClient;
use Guzzle\Plugin\Mock\MockPlugin;
use Guzzle\Http\Message\Response;


class SendVerificationTest extends \PHPUnit_Framework_TestCase
{
    const COUNTRY_CODE = '55';
    const PHONE_NUMBER = '999999999';
    const ERROR_RESPONSE = '{"test" : 233432, "status": "ERROR", "message": "ERROR_DIRECT_API_ACCESS_NOT_AVAILABLE"}';
    const SUCCESSFUL_RESPONSE = '{"test" : 233432, "status": "SUCCESS"}';

    /**
     * @var RingCaptcha
     */
    private $ringCaptcha;

    private $apiKey;
    private $appKey;

    protected function setUp()
    {
        $this->apiKey = 'test api key';
        $this->appKey = 'test app key';
    }

    private function createConfiguration($response)
    {
        $plugin = new MockPlugin();
        $plugin->addResponse(new Response(200, null, $response));
        $client = new GuzzleClient();
        $client->addSubscriber($plugin);

        $this->ringCaptcha = new RingCaptcha($this->apiKey, $this->appKey, $client);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testEmptyPhoneNumber()
    {
        $this->createConfiguration(self::SUCCESSFUL_RESPONSE);
        $countryCode = null;
        $phoneNumber = null;

        $this->ringCaptcha->sendVerificationPinCode($countryCode, $phoneNumber);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testEmptyPinCode()
    {
        $this->createConfiguration(self::SUCCESSFUL_RESPONSE);

        $this->ringCaptcha->verifyPinCode(self::COUNTRY_CODE, self::PHONE_NUMBER, null);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testEmptyMessage()
    {
        $this->createConfiguration(self::SUCCESSFUL_RESPONSE);

        $this->ringCaptcha->sendSMS(self::COUNTRY_CODE, self::PHONE_NUMBER, null);
    }

    public function testSendVerificationPinCodeSuccessful()
    {
        $this->createConfiguration(self::SUCCESSFUL_RESPONSE);

        $response = $this->ringCaptcha->sendVerificationPinCode(self::COUNTRY_CODE, self::PHONE_NUMBER);

        $this->assertArrayHasKey('status', $response);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testSendVerificationPinCodeError()
    {
        $this->createConfiguration(self::ERROR_RESPONSE);

        $this->ringCaptcha->sendVerificationPinCode(self::COUNTRY_CODE, self::PHONE_NUMBER);
    }

    public function testVerifyPinCodeSuccessful()
    {
        $this->createConfiguration(self::SUCCESSFUL_RESPONSE);

        $response = $this->ringCaptcha->verifyPinCode(self::COUNTRY_CODE, self::PHONE_NUMBER, 'code');

        $this->assertArrayHasKey('status', $response);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testVerifyPinCodeError()
    {
        $this->createConfiguration(self::ERROR_RESPONSE);

        $this->ringCaptcha->verifyPinCode(self::COUNTRY_CODE, self::PHONE_NUMBER, 'code');
    }

    public function testSendSMSSuccessful()
    {
        $this->createConfiguration(self::SUCCESSFUL_RESPONSE);

        $response = $this->ringCaptcha->sendSMS(self::COUNTRY_CODE, self::PHONE_NUMBER, 'Test message');

        $this->assertArrayHasKey('status', $response);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testSendSMSError()
    {
        $this->createConfiguration(self::ERROR_RESPONSE);

        $this->ringCaptcha->sendSMS(self::COUNTRY_CODE, self::PHONE_NUMBER, 'Test message');
    }
}
