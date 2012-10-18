<?php
/**
 * Phone Number Database Entity
 *
 * This is a Doctorine 2 definition file for zip codes.
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @package   Common\Entites
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://ci.reliv.com/confluence
 */

namespace Rcm\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Phone Number Object
 *
 * Object to use for all phone numbers in the system.
 *
 * @category  Reliv
 * @package   Common\Entites
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://ci.reliv.com/confluence
 *
 * @ORM\Entity
 * @ORM\Table(name="rcm_phone_number")
 */

class PhoneNumber
{
    /**
     * @var int Auto-Incremented Primary Key
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $phoneNumberId;

    /**
     * @ORM\Column(type="string")
     */
    protected $type;

    /**
     * @ORM\Column(type="integer")
     */
    protected $countryCode;

    /**
     * @ORM\Column(type="integer")
     */
    protected $areaCode;

    /**
     * @ORM\Column(type="integer")
     */
    protected $number;

    public function __clone()
    {
        $this->phoneNumberId = null;
        $this->type = null;
        $this->areaCode = null;
        $this->countryCode = null;
        $this->number = null;
    }

    public function setAreaCode($areaCode)
    {
        $this->areaCode = $areaCode;
    }

    public function getAreaCode()
    {
        return $this->areaCode;
    }

    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;
    }

    public function getCountryCode()
    {
        return $this->countryCode;
    }

    public function setNumber($number)
    {
        $this->number = $number;
    }

    public function getNumber()
    {
        return $this->number;
    }

    public function setPhoneNumberId($phoneNumberId)
    {
        $this->phoneNumberId = $phoneNumberId;
    }

    public function getPhoneNumberId()
    {
        return $this->phoneNumberId;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }
}