<?php

namespace Rcm\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Rcm\Exception\InvalidArgumentException;
use Rcm\Tracking\Model\Tracking;
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
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="rcm_domains",
 *     indexes={
 *         @ORM\Index(name="domain_name", columns={"domain"})
 *     })
 */
class Domain extends ApiModelTrackingAbstract implements \IteratorAggregate, Tracking
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
     * @var array
     */
    protected $domainValidatorOptions;

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
        $this->additionalDomains = new ArrayCollection();
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
     * @deprecated This validation does not belong in the entity
     *
     * Overwrite the default validator
     *
     * @param ValidatorInterface $domainValidator
     * @param array              $options
     *
     * @return void
     */
    public function setDomainValidator(
        ValidatorInterface $domainValidator,
        array $options = []
    ) {
        $this->domainValidator = get_class($domainValidator);
        $this->domainValidatorOptions = $options;
    }

    /**
     * @deprecated This validation does not belong in the entity
     *
     * getDomainValidator - Get validator
     *
     * @return Hostname|ValidatorInterface
     */
    public function getDomainValidator()
    {
        if (empty($this->domainValidator)) {
            $this->domainValidatorOptions = [
                'allow' => Hostname::ALLOW_LOCAL | Hostname::ALLOW_IP
            ];

            $this->domainValidator = Hostname::class;
        }

        $domainValidatorClass = $this->domainValidator;

        $domainValidator = new $domainValidatorClass(
            $this->domainValidatorOptions
        );

        if (method_exists($domainValidator, 'setOptions')) {
            $domainValidator->setOptions(
                $this->domainValidatorOptions
            );
        }

        return $domainValidator;
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
                'Domain name is invalid: ' . $domain
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
     * getPrimaryDomain
     *
     * @return Domain
     */
    public function getPrimaryDomain()
    {
        return $this->primaryDomain;
    }

    /**
     * Set the Primary Domain.
     *
     * @param Domain|null $primaryDomain
     *
     * @return void
     */
    public function setPrimaryDomain($primaryDomain)
    {
        if (empty($primaryDomain)) {
            $this->primaryDomain = null;
            $this->primaryId = null;

            return;
        }

        $this->primaryDomain = $primaryDomain;
        $this->primaryId = $primaryDomain->getDomainId();
    }

    /**
     * isPrimaryDomain
     *
     * @return bool
     */
    public function isPrimaryDomain()
    {
        $primary = $this->getPrimaryDomain();
        if (empty($primary)) {
            return true;
        }

        return false;
    }

    /**
     * getPrimaryDomainId
     *
     * @return int|null
     */
    public function getPrimaryDomainId()
    {
        return $this->getPrimaryId();
    }

    /**
     * getPrimaryId
     *
     * @return int|null
     */
    public function getPrimaryId()
    {
        return $this->primaryId;
    }

    /**
     * @deprecated isPrimaryDomain()
     * Check to see if this domain is the primary domain name.
     *
     * @return bool
     */
    public function isPrimary()
    {
        return $this->isPrimaryDomain();
    }

    /**
     * @deprecated use getPrimaryDomain()
     * Return the Primary Domain.
     *
     * @return \Rcm\Entity\Domain
     */
    public function getPrimary()
    {
        return $this->getPrimaryDomain();
    }

    /**
     * @deprecated use
     * Set the Primary Domain.
     *
     * @param Domain $primaryDomain Primary Domain Entity
     *
     * @return void
     */
    public function setPrimary(Domain $primaryDomain)
    {
        $this->setPrimaryDomain($primaryDomain);
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
    public function populate(array $data = [], array $ignore = ['createdByUserId', 'createdDate', 'createdReason'])
    {
        if (!empty($data['domainId']) && !in_array('domainId', $ignore)) {
            $this->setDomainId($data['domainId']);
        }
        // @bc support
        if (!empty($data['domain']) && !in_array('domainId', $ignore)) {
            $this->setDomainName($data['domain']);
        }
        if (!empty($data['domainName']) && !in_array('domainId', $ignore)) {
            $this->setDomainName($data['domainName']);
        }

        if (!empty($data['primaryDomain'])
            && !in_array('domainId', $ignore)
            && $data['primaryDomain'] instanceof Domain
        ) {
            $this->setPrimaryDomain($data['primaryDomain']);
        }
        // @bc support
        if (!empty($data['primary'])
            && !in_array('domainId', $ignore)
            && $data['primary'] instanceof Domain
        ) {
            $this->setPrimaryDomain($data['primary']);
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

        if (!in_array('isPrimaryDomain', $ignore)) {
            $data['isPrimaryDomain'] = $this->isPrimaryDomain();
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
