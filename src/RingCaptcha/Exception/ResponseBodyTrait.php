<?php

namespace RingCaptcha\Exception;

Trait ResponseBodyTrait
{
    private $responseBody = null;

    public function setResponseBody($responseBody)
    {
        $this->responseBody = $responseBody;
    }

    public function getResponseBody()
    {
        return $this->responseBody;
    }
}
