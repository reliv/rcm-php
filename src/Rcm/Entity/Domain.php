<?php
/**
 * Domain Name Database Entity
 *
 * This is a Doctrine 2 definition file for Domain Name Objects.  This file
 * is used for any module that needs to know Domain Name information.
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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Rcm\Exception\InvalidArgumentException;
use Zend\Validator\Hostname;
use Zend\Validator\ValidatorInterface;

/**
 * Country Database Entity
 *
 * This object contains registered domains names and also will note which domain
 * name is the primary domain.
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 *
 * @ORM\Entity (repositoryClass="Rcm\Repository\Domain")
 * @ORM\Table(name="rcm_domains",
 *     indexes={
 *         @ORM\Index(name="domain_name", columns={"domain"})
 *     })
 */
class Domain implements \JsonSerializable, \IteratorAggregate
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
     * @var \Rcm\Entity\Site
     *
     * @ORM\OneToOne(targetEntity="Site", mappedBy="domain")
     */
    protected $site;

    /**
     * @var \Rcm\Entity\Domain Site Object that the domain name belongs
     *                                to.
     *
     * @ORM\ManyToOne(targetEntity="Domain", inversedBy="additionalDomains")
     * @ORM\JoinColumn(
     *     name="primaryId",
     *     referencedColumnName="domainId",
     *     onDelete="CASCADE"
     * )
     */
    protected $primaryDomain;

    /**
     * @var ArrayCollection Array of Domain Objects that represent
     *                      all the additional domains that belong
     *                      to this one
     *
     * @ORM\OneToMany(targetEntity="Domain", mappedBy="primaryDomain")
     */
    protected $additionalDomains;

    /**
     * @var \Zend\Validator\ValidatorInterface
     */
    protected $domainValidator;

    /**
     * Constructor for Domain Entity.
     */
    public function __construct()
    {
        $this->additionalDomains = new ArrayCollection();
    }

    /**
     * Overwrite the default validator
     *
     * @param ValidatorInterface $domainValidator Domain Validator
     *
     * @return void
     */
    public function setDomainValidator(ValidatorInterface $domainValidator)
    {
        $this->domainValidator = $domainValidator;
    }

    /**
     * getDomainValidator - Get validator
     *
     * @return Hostname|ValidatorInterface
     */
    public function getDomainValidator()
    {

        if(empty($this->domainValidator)){
            $this->domainValidator = new Hostname(
                array(
                    'allow' => Hostname::ALLOW_LOCAL | Hostname::ALLOW_IP
                )
            );
        }

        return $this->domainValidator;
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
    public function getDomainId()
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
    public function setDomainId($domainId)
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
     *                                                 is invalid
     *
     * @return void
     */
    public function setDomainName($domain)
    {
        if (!$this->getDomainValidator()->isValid($domain)) {
            throw new InvalidArgumentException(
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
     * @param Domain $primaryDomain Primary Domain Entity
     *
     * @return void
     */
    public function setPrimary(Domain $primaryDomain)
    {
        $this->primaryDomain = $primaryDomain;
    }

    /**
     * Get all the additional domains for domain.
     *
     * @return ArrayCollection Return an Array of Domain Entities.
     */
    public function getAdditionalDomains()
    {
        if(!isset($this->additionalDomains)){
            $this->additionalDomains = new ArrayCollection();
        }
        return $this->additionalDomains;
    }

    /**
     * Add an additional domain to primary
     *
     * @param \Rcm\Entity\Domain $domain Domain Entity
     *
     * @return void
     */
    public function setAdditionalDomain(Domain $domain)
    {
        $this->additionalDomains->add($domain);
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

    /**
     * @return Site
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * @param Site $site
     */
    public function setSite(Site $site)
    {
        $this->site = $site;
    }


}
