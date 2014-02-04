<?php

namespace Rcm\Model;

use Rcm\Entity\Country;

class PhoneModel
{
    protected $phoneMask;

    function __construct(Country $country)
    {
        $phoneMasks = array(
            'USA' => '###-###-####',
            'CAN' => '###-###-####'
        );
        $iso3 = $country->getIso3();
        if (isset($phoneMasks[$iso3])) {
            $this->phoneMask = $phoneMasks[$iso3];
        }
    }


    function validatePhoneNumber($phoneNum)
    {
        if (!is_numeric($phoneNum)) {
            return false;
        }
        if (
            empty($this->phoneMask)
            || strlen($phoneNum) == substr_count($this->phoneMask, '#')
        ) {
            return true;
        }
        return false;

    }

    function formatPhoneNumber($phoneNum)
    {
        if (empty($phoneNum)) {
            return null;
        }
        if (
            empty($this->phoneMask)
            || !$this->validatePhoneNumber($phoneNum)
        ) {
            return $phoneNum;
        }
        $formattedPhone = $this->phoneMask;
        $maskLength = strlen($formattedPhone);
        $phoneI = 0;
        for ($i = 0; $i < $maskLength; $i++) {
            $maskChar = substr($formattedPhone, $i, 1);
            if ($maskChar == '#') {
                $formattedPhone[$i] = $phoneNum[$phoneI];
                $phoneI++;
            }
        }
        return $formattedPhone;
    }
}
