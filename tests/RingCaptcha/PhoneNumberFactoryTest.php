<?php
/**
 * This file is just help to development
 */
namespace Test\RingCaptcha;

use RingCaptcha\PhoneNumberFactory;


class PhoneNumberFactoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider emptyParamsProvider
     */
    public function testEmptyParams($countryCode, $phoneNumber, $description)
    {
        $internationalPhone = (new PhoneNumberFactory($countryCode, $phoneNumber))->getInternationalNumber();

        $this->assertNull($internationalPhone, 'Failed on: '.$description);
    }

    public function emptyParamsProvider()
    {
        $phoneNumbers = array(
            array(NULL, NULL, 'Test for country code and phone number params empty'),
            array('55', NULL, 'Test for phone number params empty'),
            array(NULL, '999999999', 'Test for country code params empty'),
        );

        return $phoneNumbers;
    }

    public function testWithCountryCodeAndPhoneNumber()
    {
        $countryCode = '55';
        $phoneNumber = '999999999';
        $expectedValue = '+55999999999';

        $internationalPhone = (new PhoneNumberFactory($countryCode, $phoneNumber))->getInternationalNumber();

        $this->assertEquals($internationalPhone, $expectedValue);
    }
}
