<?php
/**
 * Address Database Entity
 *
 * This is a Doctorine 2 definition file for User Objects.
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 */

namespace Rcm\Entity;

use Doctrine\ORM\Mapping as ORM,
    Rcm\Exception\InvalidArgumentException;

/**
 * Address Object
 *
 * Object to use for all addresses in the system.
 *
 * @category  Reliv
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 *
 * @ORM\Entity
 * @ORM\Table(name="rcm_addresses")
 * @ORM\DiscriminatorColumn(name="entityName", type="string")
 */
class Address
{
    /**
     * @var int Auto-Incremented Primary Key
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $addressId;

    /**
     * @ORM\Column(type="string")
     */
    protected $addressLine1;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $addressLine2;

    /**
     * @ORM\Column(type="string")
     */
    protected $city;

    /**
     * @ORM\Column(type="string")
     */
    protected $state;

    /**
     * @ORM\Column(type="string")
     */
    protected $postalCode;

    /**
     * @var \Rcm\Entity\Country country
     *
     * @ORM\ManyToOne(targetEntity="Country")
     * @ORM\JoinColumn(name="country",referencedColumnName="iso3")
     */
    protected $country;

    /**
     * @var boolean address is commercial
     * *
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $isCommercial;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $geoCode;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $phone;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $nameLine;

    /**
     * Returns true if other address is the same is this one
     * @param $otherAddress
     *
     * @return bool
     */
    public function addressSame($otherAddress)
    {
        $streetAddressOther = strtoupper($otherAddress->getAddressLine1());
        $streetAddressThis = strtoupper($this->getAddressLine1());
        $aptAddressOther = strtoupper($otherAddress->getAddressLine2());
        $aptAddressThis = strtoupper($this->getAddressLine2());
        $OtherCity = strtoupper($otherAddress->getCity());
        $ThisCity = strtoupper($this->getCity());
        $OtherState = $otherAddress->getState();
        $ThisState = $this->getState();
        $OtherCountry = $otherAddress->getCountry();
        $ThisCountry = $this->getCountry();
        $OtherGeoCode = $otherAddress->getGeoCode();
        $ThisGeoCode = $this->getGeoCode();

        return $streetAddressOther == $streetAddressThis
        && $aptAddressOther == $aptAddressThis
        && $OtherCity == $ThisCity
        && $OtherState == $ThisState
        && $OtherCountry == $ThisCountry;
       // && $OtherGeoCode == $ThisGeoCode;
    }

    

    public function __sleep()
    {
        if (!empty($this->country)
            && is_a(
                $this->country, '\Rcm\Entity\Country'
            )
        ) {
            $this->country = array(
                'iso3' => $this->country->getIso3(),
                'iso2' => $this->country->getIso2(),
                'countryName' => $this->country->getCountryName()
            );
        }

        return array(
            'addressId',
            'addressLine1',
            'addressLine2',
            'city',
            'state',
            'postalCode',
            'country',
            'isCommercial',
            'geoCode',
            'phone',
            'nameLine'
        );
    }

    public function __wakeup()
    {
        if (is_array($this->country)) {
            $wakeUpCountry = new Country();
            $wakeUpCountry->setIso3($this->country['iso3']);
            $wakeUpCountry->setIso2($this->country['iso2']);
            $wakeUpCountry->setCountryName($this->country['countryName']);

            $this->setCountry($wakeUpCountry);
        } else {
            $this->setCountry(new Country());
        }
    }

    /**
     * @param mixed $nameLine
     */
    public function setNameLine($nameLine)
    {
        $this->nameLine = $nameLine;
    }

    /**
     * @return mixed
     */
    public function getNameLine()
    {
        return $this->nameLine;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    public function setGeoCode($geoCode)
    {
        $this->geoCode = $geoCode;
    }

    public function getGeoCode()
    {
        return $this->geoCode;
    }

    /**
     * Sets the IsCommercial property
     *
     * @param boolean $isCommercial
     *
     * @return null
     *
     */
    public function setIsCommercial($isCommercial)
    {
        $this->isCommercial = $isCommercial;
    }

    /**
     * Gets the IsCommercial property
     *
     * @return boolean IsCommercial
     *
     */
    public function getIsCommercial()
    {
        return $this->isCommercial;
    }

    function __construct(
        $addressLine1 = null,
        $addressLine2 = null,
        $city = null,
        $state = null,
        $postalCode = null
    )
    {
        $this->setAddressLine1($addressLine1);
        $this->setAddressLine2($addressLine2);
        $this->setCity($city);
        $this->setState($state);
        if (isset($postalCode)) {
            $this->setPostalCode($postalCode);
        }
    }

    public function setCountryFromIso3(
        $iso3,
        \Doctrine\ORM\EntityManager $entityMgr
    )
    {
        $country = $entityMgr->getRepository('\Rcm\Entity\Country')
            ->find($iso3);
        if (!$country) {
            throw new InvalidArgumentException();
        }
        $this->setCountry($country);
    }

    public function setAddressLine1($addressLine1)
    {
        $this->addressLine1 = strip_tags($addressLine1);
    }

    public function getAddressLine1()
    {
        return $this->addressLine1;
    }

    public function setAddressLine2($addressLine2)
    {
        $this->addressLine2 = strip_tags($addressLine2);
    }

    public function getAddressLine2()
    {
        return $this->addressLine2;
    }

    public function setAddressId($addressId)
    {
        $this->addressId = $addressId;
    }

    public function getAddressId()
    {
        return $this->addressId;
    }

    public function setCity($city)
    {
        $this->city = strip_tags($city);
    }

    public function getCity()
    {
        return $this->city;
    }

    public function setCountry($country)
    {
        $this->country = $country;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function setState($state)
    {
        $this->state = $state;
    }

    public function getState()
    {
        return $this->state;
    }

    public function setPostalCode(
        $postalCode,
        $checkCountry = null,
        $entityMgrForCheckingCountry = null
    )
    {
        if ($checkCountry == 'USA') {
            $postalCodeObject = $entityMgrForCheckingCountry
                ->getRepository('\Rcm\Entity\PostalCode')
                ->findOneByPostalCode($postalCode);
            if (!$postalCodeObject) {
                throw new InvalidArgumentException();
            }
        }
        $this->postalCode = $postalCode;
    }

    function getPostalCode()
    {
        return $this->postalCode;
    }
}