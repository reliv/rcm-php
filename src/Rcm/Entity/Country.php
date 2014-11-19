<?php

/**
 * Country Database Entity
 *
 * This is a Doctrine 2 definition file for Country Objects.  This file is used
 * for any module that needs to know Country information.
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://github.com/reliv
 */

namespace Rcm\Entity;

use Doctrine\ORM\Mapping as ORM;
use Rcm\Exception\InvalidArgumentException;

/**
 * Country Database Entity
 *
 * This object contains ISO country codes as well as a map to the old site
 * countries.  Also contained is a map to internal country codes that may be
 * needed when an API used in the system does not use standard ISO codes
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 *
 * @ORM\Entity(repositoryClass="Rcm\Repository\Country")
 * @ORM\Table(name="rcm_countries")
 */
class Country implements \JsonSerializable, \IteratorAggregate
{
    /**
     * @var string ISO Three Digit Country Code
     *
     * @link http://en.wikipedia.org/wiki/ISO_3166-1 ISO Standard
     *
     * @ORM\Column(type="string", length=3, unique = true)
     * @ORM\Id
     */
    protected $iso3 = 'USA';

    /**
     * @var string ISO Two Digit Country Code
     *
     * @link http://en.wikipedia.org/wiki/ISO_3166-1 ISO Standard
     *
     * @ORM\Column(type="string", length=2, unique = true)
     */
    protected $iso2 = 'US';

    /**
     * @var string Name of Country in English
     *
     * @ORM\Column(type="string", unique = true)
     */
    protected $countryName = 'United States';

    /**
     * Sets the CountryName property
     *
     * @param string $countryName Name of the Country
     *
     * @return null
     *
     */
    public function setCountryName($countryName)
    {
        $this->countryName = $countryName;
    }

    /**
     * Gets the CountryName property
     *
     * @return string CountryName
     *
     */
    public function getCountryName()
    {
        return $this->countryName;
    }

    /**
     * Sets the Iso2 property
     *
     * @param string $iso2 ISO2 Country Code
     *
     * @return null
     * @throws InvalidArgumentException
     */
    public function setIso2($iso2)
    {
        if (strlen($iso2) != 2) {
            throw new InvalidArgumentException('ISO2 must be two characters');
        }

        $this->iso2 = $iso2;
    }

    /**
     * Gets the Iso2 property
     *
     * @return string Iso2
     *
     */
    public function getIso2()
    {
        return $this->iso2;
    }

    /**
     * Sets the Iso3 property
     *
     * @param string $iso3 ISO3 Country Code
     *
     * @return null
     * @throws InvalidArgumentException
     */
    public function setIso3($iso3)
    {
        if (strlen($iso3) != 3) {
            throw new InvalidArgumentException('ISO3 must be three characters');
        }

        $this->iso3 = $iso3;
    }

    /**
     * Gets the Iso3 property
     *
     * @return string Iso3
     *
     */
    public function getIso3()
    {
        return $this->iso3;
    }

    /**
     * jsonSerialize
     *
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * getIterator
     *
     * @return array|Traversable
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->toArray());
    }

    /**
     * toArray
     *
     * @return array
     */
    public function toArray()
    {
        return get_object_vars($this);
    }
}
