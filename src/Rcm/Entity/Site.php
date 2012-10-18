<?php
/**
 * Site Information Entity
 *
 * This is a Doctorine 2 definition file for Site info.  This file
 * is used for any module that needs to know site information.
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
 * Site Information Entity
 *
 * This object contains a list of layouts for use with the content managment
 * system.
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
 * @ORM\Table(name="rcm_sites")
 */

class Site
{
    /** 
     * @var int Auto-Incremented Primary Key
     * 
     * @ORM\Id 
     * @ORM\Column(type="integer") 
     * @ORM\GeneratedValue
     */
    protected $siteId;
    
    /** 
     * @var int Owners account number
     * 
     * @ORM\Column(type="string")
     */
    protected $owner;
    
    /**
     * @var array Array of domains that belong to the 
     *                                  site.
     * 
     * @ORM\ManyToOne(targetEntity="Domain")
     * @ORM\JoinColumn(name="domain_id", referencedColumnName="domainId")
     */
    protected $domain;
    
    /**
     * @var \Rcm\Entity\PwsInfo Information related to PWS sites
     * 
     * @ORM\OneToOne(
     *      targetEntity="PwsInfo",
     *      mappedBy="site",
     *      cascade={"persist", "remove"}
     * )
     */
    protected $pwsInfo;
    
    /** 
     * @var \Rcm\Entity\Language Default lanugage for the site
     * 
     * @ORM\ManyToOne(targetEntity="Language") 
     * @ORM\JoinColumn(
     *      name="language_id",
     *      referencedColumnName="languageId",
     *      onDelete="SET NULL"
     * )
     **/
    protected $language;
    
    /**
     * @var \Rcm\Entity\Country country
     *
     * @ORM\ManyToOne(targetEntity="Country")
     * @ORM\JoinColumn(name="country",referencedColumnName="iso3")
     */
    protected $country;
    
    /** 
     * @var string Status of site.  
     * 
     * @ORM\Column(type="string", length=2) 
     * 
     * @todo Determine the types of statuses for the site
     */
    protected $status;

    /**
     * @ORM\ManyToMany(targetEntity="Page", mappedBy="sites", indexBy="name")
     */
    protected $pages;

    /**
     * @ORM\ManyToMany(
     *     targetEntity="PluginInstance"
     * )
     * @ORM\JoinTable(
     *     name="rcm_sites_instances",
     *     joinColumns={
     *         @ORM\JoinColumn(
     *             name="site_id",
     *             referencedColumnName="siteId",
     *             onDelete="CASCADE"
     *         )
     *     },
     *     inverseJoinColumns={
     *         @ORM\JoinColumn(
     *             name="instance_id",
     *             referencedColumnName="instanceId",
     *             onDelete="CASCADE"
     *         )
     *     }
     * )
     **/
    protected $sitePlugins;
    
    /**
     * Constructor for site
     */
    public function __construct()
    {
        $this->pages = new ArrayCollection();
        $this->sitePlugins = new ArrayCollection();
    }

    /**
     * Gets the SiteId property
     *
     * @return int SiteId
     *
     */
    public function getSiteId()
    {
        return $this->siteId;
    }

    /**
     * Set the ID of the Site.  This was added for unit testing and
     * should not be used by calling scripts.  Instead please persist the object
     * with Doctrine and allow Doctrine to set this on it's own,
     *
     * @param int $siteId Unique Site ID
     *
     * @return null
     *
     */
    public function setSiteId($siteId)
    {
        $this->siteId = $siteId;
    }

    /**
     * Gets the Owner property
     *
     * @return string Owner
     *
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Sets the Owner property
     *
     * @param string $owner Owner Account Number
     *
     * @return null
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;
    }

    /**
     * Get the domains for the site
     *
     * @return \Rcm\Entity\Domain
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * Add a domain to the site
     *
     * @param \Rcm\Entity\Domain $domain Domain object to add
     *
     * @return void
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
    }

    /**
     * Gets the PwsInfo property
     *
     * @return \Rcm\Entity\PwsInfo PwsInfo
     */
    public function getPwsInfo()
    {
        return $this->pwsInfo;
    }

    /**
     * Sets the PwsInfo property
     *
     * @param \Rcm\Entity\PwsInfo $pwsInfo PWS Info Entity
     *
     * @return null
     */
    public function setPwsInfo($pwsInfo)
    {
        $this->pwsInfo = $pwsInfo;
    }

    /**
     * Get Language for the site
     *
     * @return \Rcm\Entity\Language
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Sets the Language property
     *
     * @param \Rcm\Entity\Language $language Language Entity
     *
     * @return null
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * Gets the Country property
     *
     * @return \Rcm\Entity\Country Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Sets the Country property
     *
     * @param \Rcm\Entity\Country $country Country Entity
     *
     * @return null
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * Gets the Status property
     *
     * @return string Status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Sets the Status property
     *
     * @param string $status Current status of the site.  See docs for values.
     *
     * @return null
     *
     * @todo - Add link to docs when available.
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Get all the page entities for the site.
     *
     * @return array Array of page entities
     */
    public function getPages()
    {
        return $this->pages->toArray();
    }

    /**
     * Set up a page
     *
     * @param \Rcm\Entity\Page $page Page Entity to add.
     *
     * @return null
     */
    public function addPage(\Rcm\Entity\Page $page)
    {
        $name = $page->getName();
        $this->pages[$name] = $page;
    }

    /**
     * Get page by name
     *
     * @param string  $name             Page Name to get
     * @param boolean $includeTemplates Include Templates in search?
     *
     * @return \Rcm\Entity\Page
     */
    public function getPageByName($name, $includeTemplates=false)
    {
        $page = $this->pages[$name];

        if (empty($page)) {
            return null;
        }

        if ($includeTemplates === false && $page->isTemplate()) {
            return null;
        }

        return $page;
    }

    /**
     * Get Site wide plugins
     *
     * @return array Returns an array of PluginInstance Entities
     */
    public function getSiteWidePlugins()
    {
        return $this->sitePlugins->toArray();
    }

    /**
     * Add a plugin to the site.
     * 
     * @param \Rcm\Entity\PluginInstance $plugin Site wide plugin.
     *
     * @return null
     */
    public function addSiteWidePlugin(\Rcm\Entity\PluginInstance $plugin)
    {
        $this->sitePlugins->add($plugin);
    }

    /**
     * Get Raw Plugin Instances.  Use only for unit tests
     *
     * @return \Doctrine\Common\Collections\ArrayCollection Doctrine Array
     *                                                      Collection.
     */
    public function getRawPluginInstances()
    {
        return $this->sitePlugins;
    }

    /**
     * Get Raw Page Instances.  Use only for unit tests
     *
     * @return \Doctrine\Common\Collections\ArrayCollection Doctrine Array
     *                                                      Collection.
     */
    public function getRawPageInstances()
    {
        return $this->pages;
    }

    public function getTemplates()
    {
        $templates = array();

        foreach ($this->pages as $page) {
            $publishedVersion = $page->getPublishedRevision();
            if ($page->isTemplate() && !empty($publishedVersion)) {
                $templates[] = $page;
            }
        }

        return $templates;
    }

}