<?php
/**
 * This file is just help to development
 */
namespace Tests\RingCaptcha;

use RingCaptcha\RingCaptcha;

class SendVerificationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var RingCaptcha
     */
    private $ringCaptcha;

    protected function setUp()
    {
        $apiKey = null;
        $appKey =  null;
        $this->ringCaptcha = new RingCaptcha($apiKey, $appKey);
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
        $code = null;

        $response = $this->ringCaptcha->verifyPinCode($phoneNumber, $code);

        $this->assertArrayHasKey('status', $response);
    }

    public function testSendSMS()
    {
        $phoneNumber = null;
        $message = null;

        $response = $this->ringCaptcha->sendSMS($phoneNumber, $message);

        $this->assertArrayHasKey('status', $response);
    }
}
