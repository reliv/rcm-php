<?php
/**
 * Site redirects
 *
 * This object contains a list of urls to redirect. For use with the content
 * management system.
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
use Reliv\RcmApiLib\Model\AbstractApiModel;
use Zend\Validator\Uri;
use Zend\Validator\ValidatorInterface;

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
 * @ORM\Table(name="rcm_redirects",
 *     indexes={
 *         @ORM\Index(name="redirect_request_url", columns={"requestUrl"})
 *     }
 * )
 */
class Redirect extends AbstractApiModel
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
     * @ORM\Column(type="integer")
     */
    protected $siteId = null;

    /** @var \Zend\Validator\ValidatorInterface */
    protected $urlValidator;

    /**
     * Constructor for Entity
     */
    public function __construct()
    {
        $this->urlValidator = new Uri();
    }

    /**
     * @return int|null
     */
    public function getSiteId()
    {
        return $this->siteId;
    }

    /**
     * Overwrite the default validator
     *
     * @param ValidatorInterface $urlValidator URL Validator
     *
     * @return void
     */
    public function setUrlValidator(ValidatorInterface $urlValidator)
    {
        $this->urlValidator = $urlValidator;
    }

    /**
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
        if (!$this->urlValidator->isValid($redirectUrl)) {
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
        if (!$this->urlValidator->isValid($requestUrl)) {
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
     * toArray
     *
     * @param array $ignore
     * @return array
     */
    public function toArray($ignore = ['site'])
    {
        $array = parent::toArray($ignore);
        if (!in_array('siteId', $ignore)) {
            $array['siteId'] = $this->getSiteId();
        }
        return $array;
    }
}
