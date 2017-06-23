<?php

namespace Rcm\Entity;

use Doctrine\ORM\Mapping as ORM;
use Rcm\Exception\InvalidArgumentException;
use Rcm\Tracking\Model\Tracking;
use Reliv\RcmApiLib\Model\ApiPopulatableInterface;

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
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="rcm_countries")
 */
class Country extends ApiModelTrackingAbstract implements \IteratorAggregate, Tracking
{
    /**
     * @var string ISO Three Digit Country Code
     *
     * @link http://en.wikipedia.org/wiki/ISO_3166-1 ISO Standard
     *
     * @ORM\Column(type="string", length=3)
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
     * <tracking>
     *
     * @var \DateTime Date object was first created
     *
     * @ORM\Column(type="datetime")
     */
    protected $createdDate;

    /**
     * <tracking>
     *
     * @var string User ID of creator
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $createdByUserId;

    /**
     * <tracking>
     *
     * @var string Short description of create reason
     *
     * @ORM\Column(type="string", length=512, nullable=false)
     */
    protected $createdReason = Tracking::UNKNOWN_REASON;

    /**
     * <tracking>
     *
     * @var \DateTime Date object was modified
     *
     * @ORM\Column(type="datetime")
     */
    protected $modifiedDate;

    /**
     * <tracking>
     *
     * @var string User ID of modifier
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $modifiedByUserId;

    /**
     * <tracking>
     *
     * @var string Short description of create reason
     *
     * @ORM\Column(type="string", length=512, nullable=false)
     */
    protected $modifiedReason = Tracking::UNKNOWN_REASON;

    /**
     * @param string $createdByUserId <tracking>
     * @param string $createdReason   <tracking>
     */
    public function __construct(
        string $createdByUserId,
        string $createdReason = Tracking::UNKNOWN_REASON
    ) {
        parent::__construct($createdByUserId, $createdReason);
    }

    /**
     * Get a clone with special logic
     *
     * @param string $createdByUserId
     * @param string $createdReason
     *
     * @return static
     */
    public function newInstance(
        string $createdByUserId,
        string $createdReason = Tracking::UNKNOWN_REASON
    ) {
        /** @var static $new */
        $new = parent::newInstance(
            $createdByUserId,
            $createdReason
        );

        return $new;
    }

    /**
     * getId
     *
     * @return string
     */
    public function getId()
    {
        return $this->getIso3();
    }

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
     * @return void
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
     * populate
     *
     * @param array $data
     * @param array $ignore
     *
     * @return void
     */
    public function populate(array $data = [], array $ignore = [])
    {
        if (!empty($data['iso3']) && !in_array('iso3', $ignore)) {
            $this->setIso3($data['iso3']);
        }
        if (!empty($data['iso2']) && !in_array('iso2', $ignore)) {
            $this->setIso2($data['iso2']);
        }
        if (!empty($data['countryName']) && !in_array('countryName', $ignore)) {
            $this->setCountryName($data['countryName']);
        }
    }

    /**
     * populateFromObject
     *
     * @param ApiPopulatableInterface $object
     * @param array                   $ignore
     *
     * @return void
     */
    public function populateFromObject(
        ApiPopulatableInterface $object,
        array $ignore = []
    ) {
        if ($object instanceof Country) {
            $this->populate($object->toArray(), $ignore);
        }
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
     * @return array|\Traversable
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->toArray());
    }

    /**
     * toArray
     *
     * @param array $ignore
     *
     * @return array
     */
    public function toArray($ignore = [])
    {
        return parent::toArray($ignore);
    }

    /**
     * @return void
     *
     * @ORM\PrePersist
     */
    public function assertHasTrackingData()
    {
        parent::assertHasTrackingData();
    }

    /**
     * @return void
     *
     * @ORM\PreUpdate
     */
    public function assertHasNewModifiedData()
    {
        parent::assertHasNewModifiedData();
    }
}
