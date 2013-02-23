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
 * @package   Common\Entites
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://ci.reliv.com/confluence
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
 * @package   Common\Entites
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://ci.reliv.com/confluence
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
     * @ORM\ManyToOne(targetEntity="PostalCode")
     * @ORM\JoinColumn(referencedColumnName="postalId")
     */
    protected $zip;

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
        $zip = null
    ) {
        $this->setAddressLine1($addressLine1);
        $this->setAddressLine2($addressLine2);
        $this->setCity($city);
        $this->setState($state);
        if(isset($zip)){
            $this->setZip($zip);
        }
    }

    public function setZipFromInt(
        $postalCode,
        \Doctrine\ORM\EntityManager $entityMgr
    ){
        $zip = $entityMgr->getRepository('\Rcm\Entity\PostalCode')
            ->findOneByPostalCode($postalCode);
        if(!$zip){
            throw new InvalidArgumentException();
        }
        $this->setZip($zip);
    }

    function getZipAsString(){
        if(is_object($this->getZip())){
            return $this->getZip()->getPostalCode();
        }
    }

    public function setCountryFromIso3(
        $iso3,
        \Doctrine\ORM\EntityManager $entityMgr
    ) {
        $country = $entityMgr->getRepository('\Rcm\Entity\Country')
            ->find($iso3);
        if(!$country){
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

    public function setZip($zip)
    {
        if(isset($zip)&&!is_a($zip,'\Rcm\Entity\PostalCode')){
            throw new InvalidArgumentException();
        }
        $this->zip = $zip;
    }

    /**
     * @return \Rcm\Entity\PostalCode
     */
    public function getZip()
    {
        return $this->zip;
    }
}