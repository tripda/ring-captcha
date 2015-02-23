<?php

namespace RingCaptcha\Constants;

class ErrorResponse
{
    const KEYS_NOT_DEFINED= 1;
    const PHONE_NUMBER_NOT_DEFINED = 2;
    const PHONE_OR_PIN_NOT_DEFINED = 3;
    const PHONE_OR_MESSAGE_NOT_DEFINED = 4;

    /**
     * @param string $statusCode
     * @return string
     */
    public static function getErrorMessage($statusCode)
    {
        $errorNames = self::getErrorMatrix();
        return $errorNames[$statusCode];
    }

    /**
     * @param int $statusCode
     * @return boolean
     */
    public static function hasCode($statusCode)
    {
        $errorNames = self::getErrorMatrix();
        return isset($errorNames[$statusCode]);
    }

    /**
     * @return array
     */
    public static function getErrorMatrix()
    {
        return [
            self::KEYS_NOT_DEFINED => 'AppKey or ApiKey is not defined',
            self::PHONE_NUMBER_NOT_DEFINED => 'Phone number is not defined',
            self::PHONE_OR_PIN_NOT_DEFINED => 'Phone number or pin code is not defined',
            self::PHONE_OR_MESSAGE_NOT_DEFINED => 'Phone number or message is not defined',
        ];
    }
}
