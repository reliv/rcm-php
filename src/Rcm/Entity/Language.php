<?php
/**
 * Language Database Entity
 *
 * This is a Doctorine 2 definition file for Language Objects.  This file is 
 * used for any module that needs to know language information.
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
 * Language Database Entity
 *
 * This object contains ISO language codes.
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
 * @ORM\Table(name="rcm_languages")
 */

class Language
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
    protected $languageName;
    
    /** 
     * @var string Deprecated:  Two digit langage code.  Here mainly for 
     *              backwards compatibility and use with API's that are not able
     *              to use the three digit code.  Please try use the three digit 
     *              biblical code (iso639-2b) instead.
     *
     * @link http://en.wikipedia.org/wiki/List_of_ISO_639-2_codes ISO Standard
     * 
     * @ORM\Column(type="string", length=2) 
     */
    protected $iso639_1;
    
    /** 
     * @var string Three digit ISO "bibliographic" language code.
     *
     * @link http://en.wikipedia.org/wiki/List_of_ISO_639-2_codes ISO Standard
     * 
     * @ORM\Column(type="string", length=3)
     */
    protected $iso639_2b;
    
    /** 
     * @var string Three digit ISO "terminological" language code.  This is the
     *              prefered langage code to use for the websites.  Note:
     *              there are times when this is empty.  In that case use the
     *              $iso639_2b
     *
     * @link http://en.wikipedia.org/wiki/List_of_ISO_639-2_codes ISO Standard
     * 
     * @ORM\Column(type="string", length=3) 
     */
    protected $iso639_2t;


    /**
     * @var string Old Web Language Code. Used to map old legacy code to a
     *             language entity.
     *
     * @ORM\Column(type="string")
     */
    protected $oldWebLanguage;
    
    /**
     * Function to return an array representation of the object.
     * 
     * @return array
     */
    public function toArray() 
    {
        return get_object_vars($this);
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
        if (empty($this->iso639_2t)) {
            return $this->iso639_2b;
        }
        
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
     */
    public function setIso6391($iso639_1)
    {
        $this->iso639_1 = $iso639_1;
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
     */
    public function setIso6392b($iso639_2b)
    {
        $this->iso639_2b = $iso639_2b;
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
     */
    public function setIso6392t($iso639_2t)
    {
        $this->iso639_2t = $iso639_2t;
    }

    /**
     * Get the old language code used in legacy code or site.
     *
     * @return string Old Web Language Code
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
     */
    public function setOldWebLanguage($language)
    {
        $this->oldWebLanguage = $language;
    }

}