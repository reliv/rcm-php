<?php

namespace Rcm\Entity;

use Doctrine\ORM\Mapping as ORM;
use Rcm\Exception\InvalidArgumentException;
use Rcm\Tracking\Model\Tracking;
use Reliv\RcmApiLib\Model\ApiPopulatableInterface;

/**
 * Language Database Entity
 *
 * This object contains ISO language codes.
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 *
 * @ORM\Entity(repositoryClass="Rcm\Repository\Language")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="rcm_languages")
 *
 * @SuppressWarnings("CamelCase")
 */
class Language extends ApiModelTrackingAbstract implements \IteratorAggregate, Tracking
{
    /**
     * @var int Auto-Incremented Primary Key
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $languageId;

    /**
     * @var string English name for the language
     *
     * @ORM\Column(type="string", unique = true)
     */
    protected $languageName = 'English';

    /**
     * @var string Two digit language code.  Here mainly for
     *             backwards compatibility and use with API's that are not able
     *             to use the three digit code.  Please try use the three digit
     *             code (iso639-2t) instead.
     *
     * @link http://en.wikipedia.org/wiki/List_of_ISO_639-2_codes ISO Standard
     *
     * @ORM\Column(type="string", length=2)
     */
    protected $iso639_1 = 'en';

    /**
     * @var string Three digit ISO "bibliographic" language code.
     *
     * @link http://en.wikipedia.org/wiki/List_of_ISO_639-2_codes ISO Standard
     *
     * @ORM\Column(type="string", length=3)
     */
    protected $iso639_2b = 'eng';

    /**
     * @var string Three digit ISO "terminological" language code.  This is the
     *              preferred language code to use for the websites.  Note:
     *              there are times when this is empty.  In that case use the
     *              $iso639_2b
     *
     * @link http://en.wikipedia.org/wiki/List_of_ISO_639-2_codes ISO Standard
     *
     * @ORM\Column(type="string", length=3)
     */
    protected $iso639_2t = 'eng';

    /**
     * @var string Old Web Language Code. Used to map old legacy code to a
     *             language entity.
     *
     * @ORM\Column(type="string", nullable=true)
     * @deprecated
     */
    protected $oldWebLanguage;

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
     * Alias of getThreeDigit() - Returns the three digit ISO "terminological"
     * language code of the object.
     *
     * @link http://en.wikipedia.org/wiki/List_of_ISO_639-2_codes ISO Standard
     *
     * @return string Returns three digit language code
     */
    public function getLanguage()
    {
        return $this->getThreeDigit();
    }

    /**
     * Returns the two digit ISO language code of the object.
     *
     * @link http://en.wikipedia.org/wiki/List_of_ISO_639-2_codes ISO Standard
     *
     * @return string Returns three digit language code
     */
    public function getTwoDigit()
    {
        return $this->iso639_1;
    }

    /**
     * Returns the three digit ISO "terminological" language code of the object.
     *
     * @link http://en.wikipedia.org/wiki/List_of_ISO_639-2_codes ISO Standard
     *
     * @return string Returns three digit language code
     */
    public function getThreeDigit()
    {
        return $this->iso639_2t;
    }

    /**
     * Get the unique ID for the language.
     *
     * @return int Unique Language ID
     */
    public function getLanguageId()
    {
        return $this->languageId;
    }

    /**
     * Set the ID of the Language.  This was added for unit testing and should
     * not be used by calling scripts.  Instead please persist the object
     * with Doctrine and allow Doctrine to set this on it's own,
     *
     * @param int $languageId Unique Language ID
     *
     * @return void
     */
    public function setLanguageId($languageId)
    {
        $this->languageId = $languageId;
    }

    /**
     * Get the name of the language.  Not really used but it's nice to have
     * when accessing the database.
     *
     * @return string
     */
    public function getLanguageName()
    {
        return $this->languageName;
    }

    /**
     * Set the name of the language.  Not really used but is nice to have
     * when accessing the database directly.
     *
     * @param string $languageName Name of Language
     *
     * @return void
     */
    public function setLanguageName($languageName)
    {
        $this->languageName = $languageName;
    }

    /**
     * Get ISO Two digit code - ISO 639-1
     *
     * @link http://en.wikipedia.org/wiki/List_of_ISO_639-2_codes ISO Standard
     *
     * @return string
     */
    public function getIso6391()
    {
        return $this->iso639_1;
    }

    /**
     * Set the ISO Two Digit code - ISO 639-1
     *
     * @param string $iso639_1 ISO 639-1 code
     *
     * @link http://en.wikipedia.org/wiki/List_of_ISO_639-2_codes ISO Standard
     *
     * @return void
     * @throws InvalidArgumentException
     */
    public function setIso6391($iso639_1)
    {
        if (strlen($iso639_1) != 2) {
            throw new InvalidArgumentException(
                'Iso 639-1 defines this code to be two digits in length.'
            );
        }

        $this->iso639_1 = strtolower($iso639_1);
    }

    /**
     * Get the ISO 639-2b three digit code.
     *
     * @link http://en.wikipedia.org/wiki/List_of_ISO_639-2_codes ISO Standard
     *
     * @return string
     */
    public function getIso6392b()
    {
        return $this->iso639_2b;
    }

    /**
     * Set the ISO 639-2b three digit code.
     *
     * @param string $iso639_2b ISO 639-2b three digit code
     *
     * @link http://en.wikipedia.org/wiki/List_of_ISO_639-2_codes ISO Standard
     *
     * @return void
     * @throws InvalidArgumentException
     */
    public function setIso6392b($iso639_2b)
    {
        if (strlen($iso639_2b) != 3) {
            throw new InvalidArgumentException(
                'Iso 639-2b defines this code to be three digits in length.'
            );
        }

        $this->iso639_2b = strtolower($iso639_2b);
    }

    /**
     * Get the ISO 639-2t three digit code (default)
     *
     * @link http://en.wikipedia.org/wiki/List_of_ISO_639-2_codes ISO Standard
     *
     * @return string
     */
    public function getIso6392t()
    {
        return $this->iso639_2t;
    }

    /**
     * Set the ISO 639-2t three digit code (default)
     *
     * @param string $iso639_2t ISO 639-2t three digit code
     *
     * @link http://en.wikipedia.org/wiki/List_of_ISO_639-2_codes ISO Standard
     *
     * @return void
     * @throws InvalidArgumentException
     */
    public function setIso6392t($iso639_2t)
    {
        if (strlen($iso639_2t) != 3) {
            throw new InvalidArgumentException(
                'Iso 639-2t defines this code to be three digits in length.'
            );
        }

        $this->iso639_2t = strtolower($iso639_2t);
    }

    /**
     * Get the old language code used in legacy code or site.
     *
     * @return string Old Web Language Code
     * @deprecated
     */
    public function getOldWebLanguage()
    {
        return $this->oldWebLanguage;
    }

    /**
     * Set the old language code for mapping old language codes to new
     * standards
     *
     * @param string $language Old Web Language Code
     *
     * @return void
     * @deprecated
     */
    public function setOldWebLanguage($language)
    {
        $this->oldWebLanguage = $language;
    }

    /**
     * populate
     *
     * @param array $data
     * @param array $ignore
     *
     * @return void
     */
    public function populate(array $data = [], array $ignore = ['createdByUserId', 'createdDate', 'createdReason'])
    {
        if (!empty($data['languageId']) && !in_array('languageId', $ignore)) {
            $this->setLanguageId($data['languageId']);
        }
        if (!empty($data['languageName']) && !in_array('languageName', $ignore)) {
            $this->setLanguageName($data['languageName']);
        }
        if (!empty($data['iso639_1']) && !in_array('iso639_1', $ignore)) {
            $this->setIso6391($data['iso639_1']);
        }
        if (!empty($data['iso639_2b']) && !in_array('iso639_2b', $ignore)) {
            $this->setIso6392b($data['iso639_2b']);
        }
        if (!empty($data['iso639_2t']) && !in_array('iso639_2t', $ignore)) {
            $this->setIso6392t($data['iso639_2t']);
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
        if ($object instanceof Language) {
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
        $data = parent::toArray($ignore);

        if (!in_array('iso639_1', $ignore)) {
            $data['iso639_1'] = $this->getIso6391();
        }
        if (!in_array('iso639_2b', $ignore)) {
            $data['iso639_2b'] = $this->getIso6392b();
        }
        if (!in_array('iso639_2t', $ignore)) {
            $data['iso639_2t'] = $this->getIso6392t();
        }

        return $data;
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
