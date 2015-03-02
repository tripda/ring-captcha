<?php
/**
 * This file is just help to development
 */
namespace Test\RingCaptcha;

use RingCaptcha\RingCaptcha;
use RingCaptcha\ConfigurationFactory as RingCaptchaConfigurationFactory;
use Guzzle\Http\Client as GuzzleClient;
use Guzzle\Plugin\Mock\MockPlugin;
use Guzzle\Http\Message\Response;


class SendVerificationTest extends \PHPUnit_Framework_TestCase
{
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

    private function setSuccessfulRequest()
    {
        $plugin = new MockPlugin();
        $plugin->addResponse(new Response(200, null, '{"test" : 233432, "status": "SUCCESS"}'));
        $client = new GuzzleClient();
        $client->addSubscriber($plugin);
        $configuration = (new RingCaptchaConfigurationFactory)->createRingCaptchaConfiguration(
            $this->apiKey, $this->appKey, $client);

        $this->ringCaptcha = new RingCaptcha($configuration);
    }

    private function setErrorRequest()
    {
        $plugin = new MockPlugin();
        $plugin->addResponse(new Response(200, null,
            '{"test" : 233432, "status": "ERROR", "message": "ERROR_DIRECT_API_ACCESS_NOT_AVAILABLE"}'));

        $client = new GuzzleClient();
        $client->addSubscriber($plugin);
        $configuration = (new RingCaptchaConfigurationFactory)->createRingCaptchaConfiguration(
            $this->apiKey, $this->appKey, $client);

        $this->ringCaptcha = new RingCaptcha($configuration);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testEmptyPhoneNumber()
    {
        $this->setSuccessfulRequest();
        $phoneNumber = null;

        $this->ringCaptcha->sendVerificationPinCode($phoneNumber);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testEmptyPinCode()
    {
        $this->setSuccessfulRequest();
        $phoneNumber = '+55999999999';
        $code = null;

        $this->ringCaptcha->verifyPinCode($phoneNumber, $code);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testEmptyMessage()
    {
        $this->setSuccessfulRequest();
        $phoneNumber = '+55999999999';
        $message = null;

        $this->ringCaptcha->sendSMS($phoneNumber, $message);
    }

    public function testSendVerificationPinCodeSuccessful()
    {
        $this->setSuccessfulRequest();
        $phoneNumber = '+55999999999';

        $response = $this->ringCaptcha->sendVerificationPinCode($phoneNumber);

        $this->assertArrayHasKey('status', $response);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testSendVerificationPinCodeError()
    {
        $this->setErrorRequest();
        $phoneNumber = '+55999999999';

        $this->ringCaptcha->sendVerificationPinCode($phoneNumber);
    }

    public function testVerifyPinCodeSuccessful()
    {
        $this->setSuccessfulRequest();
        $phoneNumber = '+55999999999';
        $code = 'code';

        $response = $this->ringCaptcha->verifyPinCode($phoneNumber, $code);

        $this->assertArrayHasKey('status', $response);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testVerifyPinCodeError()
    {
        $this->setErrorRequest();
        $phoneNumber = '+55999999999';
        $code = 'code';

        $this->ringCaptcha->verifyPinCode($phoneNumber, $code);
    }

    public function testSendSMSSuccessful()
    {
        $this->setSuccessfulRequest();
        $phoneNumber = '+55999999999';
        $message = 'Test message';

        $response = $this->ringCaptcha->sendSMS($phoneNumber, $message);

        $this->assertArrayHasKey('status', $response);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testSendSMSError()
    {
        $this->setErrorRequest();
        $phoneNumber = '+55999999999';
        $message = 'Test message';

        $this->ringCaptcha->sendSMS($phoneNumber, $message);
    }
}
