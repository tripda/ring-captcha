<?php

namespace RingCaptcha\Exception;

use RingCaptcha\Constant\ErrorResponse;

class MessageNotDefined extends \InvalidArgumentException
{
    use ResponseBodyTrait;

    public function __construct()
    {
        parent::__construct(ErrorResponse::getErrorMessage(ErrorResponse::MESSAGE_NOT_DEFINED));
    }
}