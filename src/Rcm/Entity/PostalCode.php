<?php
/**
 * Postal Code / Zip code Database Entity
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
 */

namespace Rcm\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Postal Code Object
 *
 * Object to use for all addresses in the system.
 *
 * @category  Reliv
 * @package   Common\Entites
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 *
 * @ORM\Entity
 * @ORM\Table(name="rcm_postal_codes")
 */
class PostalCode
{
    /**
     * @var int Auto-Incremented Primary Key
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $postalId;

    /**
     * @ORM\Column(type="string")
     */
    protected $postalCode;

    /**
     * @ORM\Column(type="string")
     */
    protected $city;

    /**
     * @ORM\Column(type="string")
     */
    protected $county;

    /**
     * @ORM\Column(type="string")
     */
    protected $state;

    /**
     * @ORM\Column(type="integer")
     */
    protected $areaCode;

    /**
     * @ORM\Column(type="string")
     */
    protected $fipsCode;

    /**
     * @ORM\Column(type="string")
     */
    protected $timeZone;

    /**
     * @ORM\Column(type="string")
     */
    protected $usesDaylightSavings;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=6)
     */
    protected $latitude;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=6)
     */
    protected $longitude;

    public function setAreaCode($areaCode)
    {
        $this->areaCode = $areaCode;
    }

    public function getAreaCode()
    {
        return $this->areaCode;
    }

    public function setCity($city)
    {
        $this->city = $city;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function setCounty($county)
    {
        $this->county = $county;
    }

    public function getCounty()
    {
        return $this->county;
    }

    public function setFipsCode($fipsCode)
    {
        $this->fipsCode = $fipsCode;
    }

    public function getFipsCode()
    {
        return $this->fipsCode;
    }

    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    public function getLatitude()
    {
        return $this->latitude;
    }

    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

    public function getLongitude()
    {
        return $this->longitude;
    }

    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;
    }

    public function getPostalCode()
    {
        return $this->postalCode;
    }

    public function setPostalId($postalId)
    {
        $this->postalId = $postalId;
    }

    public function getPostalId()
    {
        return $this->postalId;
    }

    public function setState($state)
    {
        $this->state = $state;
    }

    public function getState()
    {
        return $this->state;
    }

    public function setTimeZone($timeZone)
    {
        $this->timeZone = $timeZone;
    }

    public function getTimeZone()
    {
        return $this->timeZone;
    }

    public function setUsesDaylightSavings($usesDaylightSavings)
    {
        $this->usesDaylightSavings = $usesDaylightSavings;
    }

    public function getUsesDaylightSavings()
    {
        return $this->usesDaylightSavings;
    }
}