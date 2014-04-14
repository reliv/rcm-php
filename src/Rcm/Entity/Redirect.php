<?php

namespace Rcm\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\Validator\Uri;

/**
 * Site redirects
 *
 * This object contains a list of urls to redirect. For use with the content management
 * system.
 *
 * @category  Reliv
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 *
 * @ORM\Entity
 * @ORM\Table(name="rcm_redirects")
 */
class Redirect
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
     * @ORM\JoinColumn(name="siteId", referencedColumnName="siteId", onDelete="CASCADE")
     **/
    protected $site;

    /**
     * @param int $redirectId
     */
    public function setRedirectId($redirectId)
    {
        $this->redirectId = $redirectId;
    }

    /**
     * @return int
     */
    public function getRedirectId()
    {
        return $this->redirectId;
    }

    /**
     * @param $redirectUrl
     *
     * @throws \Exception
     */
    public function setRedirectUrl($redirectUrl)
    {
        $validator = new Uri();

        if ($validator->isValid($redirectUrl)) {
            $this->redirectUrl = $redirectUrl;
        } else {
            throw new \Exception('URL provided is invalid');
        }
    }

    /**
     * @return string
     */
    public function getRedirectUrl()
    {
        return $this->redirectUrl;
    }

    /**
     * @param $requestUrl
     *
     * @throws \Exception
     */
    public function setRequestUrl($requestUrl)
    {
        $validator = new Uri();

        if ($validator->isValid($requestUrl)) {
            $this->requestUrl = $requestUrl;
        } else {
            throw new \Exception('URL provided is invalid');
        }

    }

    /**
     * @return string
     */
    public function getRequestUrl()
    {
        return $this->requestUrl;
    }

    /**
     * @param \Rcm\Entity\Site $site
     */
    public function setSite($site)
    {
        $this->site = $site;
    }

    /**
     * @return \Rcm\Entity\Site
     */
    public function getSite()
    {
        return $this->site;
    }

}