<?php

namespace RingCaptcha\Exception;

use RingCaptcha\Constant\ErrorResponse;

class PinCodeNotDefined extends \InvalidArgumentException
{
    use ResponseBodyTrait;

    public function __construct()
    {
        parent::__construct(ErrorResponse::getErrorMessage(ErrorResponse::PIN_CODE_NOT_DEFINED));
    }
}