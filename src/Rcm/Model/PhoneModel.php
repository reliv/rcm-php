<?php

namespace Rcm\Model;

class PhoneModel
{
    protected $phoneNumberMask="###-###-###";

    public function setPhoneNumberMask($phoneNumberMask)
    {
        $this->phoneNumberMask = $phoneNumberMask;
    }

    public function getPhoneNumberMask()
    {
        return $this->phoneNumberMask;
    }

    function validatePhoneNumber($phoneNum)
    {
        $phoneNum = $this->digitsOnly($phoneNum);
        if (strlen($phoneNum) == substr_count($this->phoneNumberMask, '#')) {
            return true;
        }
        return false;

    }

    function formatPhoneNumber($phoneNum){
        if(!$this->validatePhoneNumber($phoneNum)){
            throw new \Rcm\Exception\RuntimeException(
                'Cannot format invalid phone number: "'.$phoneNum.'"'
            );
        }
        $formattedPhone=$this->phoneNumberMask;
        $maskLength=strlen($formattedPhone);
        $phoneI=0;
        for($i=0;$i<$maskLength;$i++){
            $maskChar = substr($formattedPhone,$i,1);
            if($maskChar=='#'){
                $formattedPhone[$i]=$phoneNum[$phoneI];
                $phoneI++;
            }
        }
        return $formattedPhone;
    }

    function digitsOnly($value)
    {
        return preg_replace('/[^0-9]*/', '', $value);
    }
}
