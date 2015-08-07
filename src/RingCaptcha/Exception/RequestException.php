<?php

namespace RingCaptcha\Exception;

class RequestException extends \RuntimeException
{
    use ResponseBodyTrait;

    public function __construct($exceptionMessage, $requestBody)
    {
        $this->setResponseBody($requestBody);
        parent::__construct($exceptionMessage);
    }
}