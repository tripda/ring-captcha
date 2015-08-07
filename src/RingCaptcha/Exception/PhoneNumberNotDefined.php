<?php

namespace RingCaptcha\Exception;

use RingCaptcha\Constant\ErrorResponse;

class PhoneNumberNotDefined extends \InvalidArgumentException
{
    use ResponseBodyTrait;

    public function __construct()
    {
       parent::__construct(ErrorResponse::getErrorMessage(ErrorResponse::PHONE_NUMBER_NOT_DEFINED));
    }
}