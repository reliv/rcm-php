<?php
/**
 * Domain Name Database Entity
 *
 * This is a Doctorine 2 definition file for Domain Name Objects.  This file 
 * is used for any module that needs to know Domain Name information.
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
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Country Database Entity
 *
 * This object contains registered domains names and also will note which domain
 * name is the primary domain. 
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
 * @ORM\Table(name="rcm_domains")
 */

class Domain
{
    /** 
     * @var int Auto-Incremented Primary Key
     * 
     * @ORM\Id 
     * @ORM\Column(type="integer") 
     * @ORM\GeneratedValue
     */
    protected $domainId;
    
    /** 
     * @var string Valid Domain Name
     * 
     * @ORM\Column(type="string") 
     */
    protected $domain;

    /**
     * @var \Rcm\Entity\Domain Site Object that the domain name belongs
     *                                to.
     *
     * @ORM\ManyToOne(targetEntity="Domain", inversedBy="additionalDomains")
     * @ORM\JoinColumn(name="primary_id", referencedColumnName="domainId")
     */
    protected $primaryDomain;

    /**
     * @var \Rcm\Entity\Domain[] Array of Domain Objects that represent
     *                                   all the additional domains that belong
     *                                   to this one
     *
     * @ORM\OneToMany(targetEntity="Domain", mappedBy="primaryDomain")
     */
    protected $additionalDomains;

    /**
     * @var \Rcm\Entity\Language this domain's default language
     *
     * @ORM\ManyToOne(targetEntity="Language")
     * @ORM\JoinColumn(name="defaultLanguageId",referencedColumnName="languageId")
     */
    protected $defaultLanguage;

    /**
     * Constructor for Domain Entity.
     *
     * @returns void
     */
    public function __construct()
    {
        $this->additionalDomains = new ArrayCollection();
    }

    /**
     * Function to return an array representation of the object.
     *
     * @param bool $skipPrimary    Used to prevent infinite loops.  Should not
     *                             be used by calling scripts.
     * @param bool $skipAdditional Used to prevent infinite loops.  Should not
     *                             be used by calling scripts.
     *
     * @return array
     *
     * @SuppressWarnings(PHPMD)
     */
    public function toArray($skipPrimary=false, $skipAdditional=false)
    {
        $array = array(
            'domainId' => $this->domainId,
            'domain' => $this->domain
        );

        if (!empty($this->primaryDomain)
            && is_a($this->primaryDomain, '\Rcm\Entity\Domain')
            && $skipPrimary === false
        ) {
            $array['primaryDomain']
                = $this->primaryDomain->toArray(false, true);
        } elseif ($skipPrimary === false) {
            $array['primaryDomain'] = null;
        }

        if (!empty($this->defaultLanguage)
            && is_a($this->defaultLanguage, '\Rcm\Entity\Language')
        ) {
            $array['defaultLanguage'] = $this->defaultLanguage->getLanguage();
        } else {
            $array['defaultLanguage'] = null;
        }

        $additionalDomains = $this->additionalDomains->toArray();

        if (!empty($additionalDomains)
            && is_array($additionalDomains)
            && $skipAdditional === false
        ) {
            foreach ($additionalDomains as $additionalDomain) {
                $array['additionalDomains'][]
                    = $additionalDomain->toArray(true);
            }
        } elseif ($skipAdditional === false) {
            $array['additionalDomains'] = array();
        }

        $array['isPrimary'] = $this->isPrimary();

        return $array;
    }

    /**
     * Check to see if this domain is the primary domain name.
     *
     * @return bool
     */
    public function isPrimary()
    {
        if (empty($this->primaryDomain)) {
            return true;
        }

        return false;
    }

    /**
     * Get the Unique ID of the Domain.
     *
     * @return int Unique Domain ID
     */
    public function getId()
    {
        return $this->domainId;
    }

    /**
     * Set the ID of the Domain.  This was added for unit testing and should
     * not be used by calling scripts.  Instead please persist the object
     * with Doctrine and allow Doctrine to set this on it's own,
     *
     * @param int $domainId Unique Domain ID
     *
     * @return void
     */
    public function setId($domainId)
    {
        $this->domainId = $domainId;
    }

    /**
     * Get the actual domain name.
     *
     * @return string
     */
    public function getDomainName()
    {
        return $this->domain;
    }

    /**
     * Set the domain name
     *
     * @param string $domain Domain name of object
     *
     * @throws \Rcm\Exception\InvalidArgumentException If domain name
     *                                                         is invalid
     *
     * @return void
     */
    public function setDomainName($domain)
    {
        if (!$this->domainIsValid($domain)) {
            throw new \Rcm\Exception\InvalidArgumentException(
                'Domain name is invalid'
            );
        }

        $this->domain = $domain;
    }

    /**
     * Return the Primary Domain.
     *
     * @return \Rcm\Entity\Domain
     */
    public function getPrimary()
    {
        return $this->primaryDomain;
    }

    /**
     * Set the Primary Domain.
     *
     * @param \Rcm\Entity\Domain $primaryDomain Primary Domain Entity
     *
     * @return void
     */
    public function setPrimary($primaryDomain)
    {
        $this->primaryDomain = $primaryDomain;
    }

    /**
     * Get all the additional domains for domain.
     *
     * @return \Rcm\Entity\Domain[] Return an Array of Domain Entities.
     */
    public function getAdditionalDomains()
    {
        return $this->additionalDomains->toArray();
    }

    /**
     * Add an additional domain to primary
     *
     * @param \Rcm\Entity\Domain $domain Domain Entity
     *
     * @return void
     */
    public function setAdditionalDomain(
        \Rcm\Entity\Domain $domain
    ) {
        $this->additionalDomains->add($domain);
    }

    
    /**
     * Check Domain Name
     * 
     * @param string $domain Domain Name to check.
     * 
     * @return boolean
     */
    public function domainIsValid($domain)
    {
        /*
         * @link http://regexlib.com/REDetails.aspx?regexp_id=391 Pattern
         */
        $pattern 
            ='/^([a-zA-Z0-9]([a-zA-Z0-9\-]{0,61}'
                .'[a-zA-Z0-9])?\.)+[a-zA-Z]{2,6}$/';
        
        if (preg_match($pattern, $domain)) {
            return true;
        }
        
        return false;
    }

    /**
     * Sets the DefaultLanguage property
     *
     * @param \Rcm\Entity\Language $defaultLanguage DefaultLanguage
     *
     * @return null
     *
     */
    public function setDefaultLanguage(
        \Rcm\Entity\Language $defaultLanguage
    ) {
        $this->defaultLanguage = $defaultLanguage;
    }

    /**
     * Gets the DefaultLanguage property
     *
     * @return \Rcm\Entity\Language defaultLanguage DefaultLanguage
     *
     */
    public function getDefaultLanguage()
    {
        return $this->defaultLanguage;
    }

    /**
     * Get Raw Additional Domains.  Use only for unit tests
     *
     * @return \Doctrine\Common\Collections\ArrayCollection Doctrine Array
     *                                                      Collection.
     */
    public function getRawAdditionalDomains()
    {
        return $this->additionalDomains;
    }

    /**
     * Set default language to null.  Used only for Unit Tests
     *
     * @return void
     */
    public function clearLanguageForUnitTests()
    {
        $this->defaultLanguage = null;
    }
}