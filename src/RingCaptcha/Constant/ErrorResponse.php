<?php

namespace RingCaptcha\Constant;

class ErrorResponse
{
    const KEYS_NOT_DEFINED= 1;
    const PHONE_NUMBER_NOT_DEFINED = 2;
    const PIN_CODE_NOT_DEFINED = 3;
    const MESSAGE_NOT_DEFINED = 4;

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
            self::PIN_CODE_NOT_DEFINED => 'Pin code is not defined',
            self::MESSAGE_NOT_DEFINED => 'Message is not defined',
        ];
    }
}
