<?php

namespace Rcm\Entity;

use Doctrine\ORM\Mapping as ORM;
use Rcm\Exception\InvalidArgumentException;
use Rcm\Tracking\Model\Tracking;
use Zend\Validator\Uri;

/**
 * Site redirects
 *
 * This object contains a list of urls to redirect. For use with the content
 * management system.
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 *
 * @ORM\Entity (repositoryClass="Rcm\Repository\Redirect")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="rcm_redirects",
 *     indexes={
 *         @ORM\Index(name="redirect_request_url_siteId", columns={"requestUrl", "siteId"})
 *     }
 * )
 */
class Redirect extends ApiModelTrackingAbstract implements Tracking
{
    /**
     * @var int Auto-Incremented Primary Key
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $redirectId;

    /**
     * @var string Request URL
     *
     * @ORM\Column(type="string")
     */
    protected $requestUrl;

    /**
     * @var string Redirect URL
     *
     * @ORM\Column(type="string")
     */
    protected $redirectUrl;

    /**
     * @var \Rcm\Entity\Site
     *
     * @ORM\ManyToOne(targetEntity="Site")
     * @ORM\JoinColumn(
     *     name="siteId",
     *     referencedColumnName="siteId",
     *     onDelete="CASCADE"
     * )
     **/
    protected $site;

    /**
     * @var int|null $siteId
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $siteId = null;

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
     * getUrlValidator
     *
     * @return Uri
     */
    protected function getUrlValidator()
    {
        return new Uri();
    }

    /**
     * @deprecated Do NOT use
     * Set the Redirect Id.  This was added for unit testing and
     * should not be used by calling scripts.  Instead please persist the object
     * with Doctrine and allow Doctrine to set this on it's own.
     *
     * @param int $redirectId Redirect ID
     *
     * @return void
     */
    public function setRedirectId($redirectId)
    {
        $this->redirectId = $redirectId;
    }

    /**
     * Get the Redirect Id
     *
     * @return int
     */
    public function getRedirectId()
    {
        return $this->redirectId;
    }

    /**
     * Set the Redirect URL
     *
     * @param string $redirectUrl Redirect URL
     *
     * @return void
     * @throws InvalidArgumentException
     */
    public function setRedirectUrl($redirectUrl)
    {

        if (!$this->getUrlValidator()->isValid($redirectUrl)) {
            throw new InvalidArgumentException('URL provided is invalid');
        }

        $this->redirectUrl = $redirectUrl;
    }

    /**
     * Get Redirect URL
     *
     * @return string
     */
    public function getRedirectUrl()
    {
        return $this->redirectUrl;
    }

    /**
     * Set the Request URL to redirect
     *
     * @param string $requestUrl Request URL to redirect
     *
     * @return void
     * @throws \Exception
     */
    public function setRequestUrl($requestUrl)
    {
        if (!$this->getUrlValidator()->isValid($requestUrl)) {
            throw new InvalidArgumentException('URL provided is invalid');
        }

        $this->requestUrl = $requestUrl;
    }

    /**
     * Return the Request URL to Redirect
     *
     * @return string
     */
    public function getRequestUrl()
    {
        return $this->requestUrl;
    }

    /**
     * Set the Site the redirect belongs to
     *
     * @param \Rcm\Entity\Site $site Site Entity
     *
     * @return void
     */
    public function setSite($site)
    {
        if ($site === null) {
            $this->siteId = null;
            $this->site = null;

            return;
        }
        $this->siteId = $site->getSiteId();

        $this->site = $site;
    }

    /**
     * Get the site the redirect belongs to
     *
     * @return \Rcm\Entity\Site
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * setSiteId
     *
     * @param $siteId
     *
     * @return void
     */
    public function setSiteId($siteId)
    {
        $this->siteId = $siteId;
    }

    /**
     * getSiteId
     *
     * @return int|null
     */
    public function getSiteId()
    {
        return $this->siteId;
    }

    /**
     * toArray
     *
     * @param array $ignore
     *
     * @return array
     */
    public function toArray($ignore = ['site'])
    {
        $array = parent::toArray($ignore);
        if (!in_array('siteId', $ignore)) {
            $array['siteId'] = $this->getSiteId();
        }

        // @bc support
        if (!in_array('domain', $ignore)) {
            $site = $this->site;
            if ($site !== null) {
                $array['domain'] = $site->getDomain()->getDomainName();
            }
        }

        if (!in_array('domainName', $ignore)) {
            $site = $this->site;
            if ($site !== null) {
                $array['domainName'] = $site->getDomain()->getDomainName();
            }
        }

        return $array;
    }

    /**
     * <tracking>
     *
     * @return void
     *
     * @ORM\PrePersist
     */
    public function assertHasTrackingData()
    {
        parent::assertHasTrackingData();
    }

    /**
     * <tracking>
     *
     * @return void
     *
     * @ORM\PreUpdate
     */
    public function assertHasNewModifiedData()
    {
        parent::assertHasNewModifiedData();
    }
}
