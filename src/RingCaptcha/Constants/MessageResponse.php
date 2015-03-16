<?php

namespace RingCaptcha\Constants;

class MessageResponse
{
    protected static $messageCodes = [
        'ERROR_INVALID_SECRET_KEY' =>
            'Using incorrect keys or domain/application name',
        'ERROR_INVALID_APP_KEY' =>
            'Using incorrect keys or domain/application name',
        'ERROR_INVALID_DOMAIN' =>
            'Using incorrect keys or domain/application name',
        'ERROR_INVALID_API_KEY' =>
            'Using incorrect keys or domain/application name',
        'ERROR_INTERNAL_SERVER_ERROR' =>
            'Unknown error',
        'ERROR_DIRECT_API_ACCESS_NOT_AVAILABLE' =>
            'Trying to access an inactive feature or API incorrectly',
        'ERROR_WEB_ACCESS_NOT_AVAILABLE' =>
            'Trying to access an inactive feature or API incorrectly',
        'ERROR_MOBILE_ACCESS_NOT_AVAILABLE' =>
            'Trying to access an inactive feature or API incorrectly',
        'ERROR_INSTANT_VALIDATION_NOT_AVAILABLE' =>
            'Trying to access an inactive feature or API incorrectly',
        'ERROR_SERVICE_NOT_AVAILABLE' =>
            'Trying to access an inactive feature or API incorrectly',
        'ERROR_INVALID_SERVICE' =>
            'Trying to access an inactive feature or API incorrectly',
        'ERROR_INVALID_NUMBER' =>
            'Phone number is incorrect, either the area code is missing or it contains invalid numbers',
        'ERROR_WAIT_TO_RETRY' => 'Retrying more often than “retry_in” field allows',
        'ERROR_MAX_ATTEMPTS_REACHED' =>
            'Retrying more times with the same active token or more frequently than expected',
        'ERROR_MAX_VALIDATIONS_REACHED' =>
            'Retrying more times with the same active token or more frequently than expected',
        'ERROR_INVALID_SESSION' =>
            'Token is incorrect or has already expired/been verified',
        'ERROR_INVALID_PIN_CODE' =>
            'PIN code is incorrect',
    ];

    public static function getMessageResponse($messageCode)
    {
        return self::$messageCodes[$messageCode];
    }
}
