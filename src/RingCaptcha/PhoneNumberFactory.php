<?php

namespace RingCaptcha;

class PhoneNumberFactory
{
    private $countryCode;
    private $number;

    public function __construct($countryCode, $number)
    {
        $this->countryCode = $countryCode;
        $this->number      = $number;
    }

    public function getCountryCode()
    {
        return $this->countryCode;
    }

    public function getNumber()
    {
        return $this->number;
    }

    public function getInternationalNumber()
    {
        if ($this->getCountryCode() == NULL || $this->getNumber() == NULL) {
            return NULL;
        }

        return sprintf('+%s%s', $this->getCountryCode(), $this->getNumber());
    }
}
