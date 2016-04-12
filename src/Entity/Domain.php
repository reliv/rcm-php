<?php

namespace Rcm\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Rcm\Exception\InvalidArgumentException;
use Reliv\RcmApiLib\Model\ApiPopulatableInterface;
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
class Domain extends AbstractApiModel implements \IteratorAggregate
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
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $primaryId;

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
        if (empty($this->domainValidator)) {
            $this->domainValidator = new Hostname(
                [
                    'allow' => Hostname::ALLOW_LOCAL | Hostname::ALLOW_IP
                ]
            );
        }

        return $this->domainValidator;
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
        $domain = strtolower($domain);
        if (!$this->getDomainValidator()->isValid($domain)) {
            throw new InvalidArgumentException(
                'Domain name is invalid'
            );
        }

        $this->domain = $domain;
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

    /**
     * getSiteId
     *
     * @return int|null
     */
    public function getSiteId()
    {
        $site = $this->getSite();
        if (empty($site)) {
            return null;
        }

        return $site->getSiteId();
    }

    /**
     * Check to see if this domain is the primary domain name.
     *
     * @return bool
     */
    public function isPrimary()
    {
        $primary = $this->getPrimary();
        if (empty($primary)) {
            return true;
        }

        return false;
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
        $this->primaryId = $primaryDomain->getDomainId();
    }

    /**
     * getPrimaryDomainId
     *
     * @return null
     */
    public function getPrimaryId()
    {
        return $this->primaryId;
    }

    /**
     * Get all the additional domains for domain.
     *
     * @return ArrayCollection Return an Array of Domain Entities.
     */
    public function getAdditionalDomains()
    {
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
     * populate
     *
     * @param array $data
     * @param array $ignore
     *
     * @return void
     */
    public function populate(array $data = [], array $ignore = [])
    {
        if (!empty($data['domainId'])) {
            $this->setDomainId($data['domainId']);
        }
        // @bc support
        if (!empty($data['domain'])) {
            $this->setDomainName($data['domain']);
        }
        if (!empty($data['domainName'])) {
            $this->setDomainName($data['domainName']);
        }
        // @bc support
        if (!empty($data['primaryDomain'])
            && $data['primaryDomain'] instanceof Domain
        ) {
            $this->setPrimary($data['primaryDomain']);
        }
        if (!empty($data['primary'])
            && $data['primary'] instanceof Domain
        ) {
            $this->setPrimary($data['primary']);
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
        if ($object instanceof Domain) {
            $this->populate($object->toArray(['additionalDomains']), $ignore);
        }
    }

    /**
     * jsonSerialize
     *
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return $this->toArray(['additionalDomains', 'domainValidator', 'site']);
    }

    /**
     * getIterator
     *
     * @return array|\Traversable
     */
    public function getIterator()
    {
        return new \ArrayIterator(
            $this->toArray(['additionalDomains', 'domainValidator', 'site'])
        );
    }

    /**
     * toArray
     *
     * @param array $ignore
     *
     * @return array
     */
    public function toArray(
        $ignore = ['additionalDomains', 'domainValidator', 'site']
    ) {
        $data = parent::toArray($ignore);

        if (!in_array('siteId', $ignore)) {
            $data['siteId'] = $this->getSiteId();
        }

        if (!in_array('additionalDomains', $ignore)) {
            $data['additionalDomains'] = $this->modelArrayToArray(
                $this->getAdditionalDomains()->toArray(),
                ['additionalDomains', 'domainValidator', 'site']
            );
        }

        // @bc support
        if (!in_array('domain', $ignore)) {
            $data['domain'] = $this->getDomainName();
        }

        if (!in_array('isPrimary', $ignore)) {
            $data['isPrimary'] = $this->isPrimary();
        }

        return $data;
    }
}
