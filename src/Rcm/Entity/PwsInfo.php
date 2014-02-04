<?php
/**
 * PWS Information Entity
 *
 * This is a Doctorine 2 definition file for PWS Info.  This file
 * is used for any module that needs to know PWS information information.
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
 */

namespace Rcm\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PWS Information Entity
 *
 * This object contains contains things like when a PWS site was
 * activated/canceled
 *
 * @category  Reliv
 * @package   Common\Entites
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 *
 * @ORM\Entity
 * @ORM\Table(name="rcm_sites_pws_info")
 */
class PwsInfo
{
    /**
     * @var int Auto-Incremented Primary Key
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $pwsId;

    /**
     * @var \Rcm\Entity\Site Site Object that the domain name belongs
     *                                to.
     *
     * @ORM\ManyToOne(targetEntity="Site", inversedBy="pwsInfo")
     * @ORM\JoinColumn(name="site_id", referencedColumnName="siteId")
     */
    protected $site;

    /**
     * @var \DateTime Timestamp of when a site was active
     *
     * @ORM\Column(type="datetime")
     */
    protected $activeDate;

    /**
     * @var \DateTime Timestamp of when a site was canceled
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $cancelDate;

    /**
     * @var \DateTime Timestamp of when a site activation was last updated
     *
     * @ORM\Column(type="datetime")
     */
    protected $lastUpdated;

    /**
     * Gets the PwsId property
     *
     * @return int PwsId
     *
     */
    public function getPwsId()
    {
        return $this->pwsId;
    }

    /**
     * Set the ID of the PWS.  This was added for unit testing and should
     * not be used by calling scripts.  Instead please persist the object
     * with Doctrine and allow Doctrine to set this on it's own,
     *
     * @param int $pwsId Unique Identifier
     *
     * @return null
     *
     */
    public function setPwsId($pwsId)
    {
        $this->pwsId = $pwsId;
    }

    /**
     * Get the site the PWS belongs to.
     *
     * @return \Rcm\Entity\Site
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * Add the pws to a site.  Or in Doctorine case, add a site to the
     * domain name.
     *
     * @param \Rcm\Entity\Site $site Object of the site.
     *
     * @return void
     */
    public function setSite(\Rcm\Entity\Site $site)
    {
        $this->site = $site;
    }

    /**
     * Gets the ActiveDate property
     *
     * @return \DateTime ActiveDate
     */
    public function getActiveDate()
    {
        return $this->activeDate;
    }

    /**
     * Sets the ActiveDate property
     *
     * @param \DateTime $activeDate Date the PWS was activated
     *
     * @return null
     */
    public function setActiveDate(\DateTime $activeDate)
    {
        $this->activeDate = $activeDate;
    }

    /**
     * Gets the CancelDate property
     *
     * @return \DateTime CancelDate PHP DateTime object of when the site was
     *                   canceled.
     *
     */
    public function getCancelDate()
    {
        return $this->cancelDate;
    }

    /**
     * Sets the CancelDate property
     *
     * @param \DateTime $cancelDate PHP DateTime object of when the site was
     *                              canceled.
     *
     * @return null
     */
    public function setCancelDate(\DateTime $cancelDate)
    {
        $this->cancelDate = $cancelDate;
    }

    /**
     * Gets the LastUpdated property
     *
     * @return \DateTime LastUpdated
     */
    public function getLastUpdated()
    {
        return $this->lastUpdated;
    }

    /**
     * Sets the LastUpdated property
     *
     * @param \DateTime $lastUpdated PHP DateTime object of last updated date
     *
     * @return null
     *
     */
    public function setLastUpdated(\DateTime $lastUpdated)
    {
        $this->lastUpdated = $lastUpdated;
    }

    /**
     * Function to return an array representation of the object.
     *
     * @return array Array representation of the object
     */
    public function toArray()
    {
        return get_object_vars($this);
    }
}