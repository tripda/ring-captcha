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

    protected function setUp()
    {
        $apiKey = null;
        $appKey = null;
        $plugin = new MockPlugin();
        $plugin->addResponse(new Response(200, null, '{"test" : 233432, "status": "SUCCESS"}'));
        $client = new GuzzleClient();
        $client->addSubscriber($plugin);
        $configuration = (new RingCaptchaConfigurationFactory)->createRingCaptchaConfiguration($apiKey, $appKey, $client);

        $this->ringCaptcha = new RingCaptcha($configuration);
    }

    public function testSendVerificationPinCode()
    {
        $phoneNumber = null;

        $response = $this->ringCaptcha->sendVerificationPinCode($phoneNumber);

        $this->assertArrayHasKey('status', $response);
    }

    public function testVerifyPinCode()
    {
        $phoneNumber = null;
        $code = 'code';

        $response = $this->ringCaptcha->verifyPinCode($phoneNumber, $code);

        $this->assertArrayHasKey('status', $response);
    }

    public function testSendSMS()
    {
        $phoneNumber = null;
        $message = 'Test message';

        $response = $this->ringCaptcha->sendSMS($phoneNumber, $message);

        $this->assertArrayHasKey('status', $response);
    }
}
