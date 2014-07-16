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
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
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
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
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
     * @ORM\JoinColumn(name="domainId", referencedColumnName="domainId", onDelete="SET NULL")
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
     * @var string Theme of site
     *
     * @ORM\Column(type="string")
     *
     * @todo Determine the types of statuses for the site
     */
    protected $theme;

    /**
     * @var \Rcm\Entity\Language Default lanugage for the site
     *
     * @ORM\ManyToOne(targetEntity="Language")
     * @ORM\JoinColumn(
     *      name="languageId",
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
     * @var array Array of pages
     *
     * @ORM\OneToMany(
     *     targetEntity="Page",
     *     mappedBy="site",
     *     cascade={"persist", "remove"}
     * )
     */
    protected $pages;

    /**
     * @ORM\ManyToMany(
     *     targetEntity="PluginInstance",
     *     fetch="EAGER",
     *     cascade={"persist", "remove"}
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
     * @var boolean Status of site.
     *
     * @ORM\Column(type="boolean")
     **/
    protected $loginRequired = false;

    /**
     * @var string URL to login page.
     *
     * @ORM\Column(type="string", nullable=true)
     **/
    protected $loginPage;

    /**
     * @var string Comma seperated list of account types permitted
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $permittedAccountTypes;

    /**
     * Constructor for site
     */
    public function __construct()
    {
        $this->pages = new ArrayCollection();
        $this->sitePlugins = new ArrayCollection();
    }

    public function __clone()
    {
        if ($this->siteId) {
            $this->setSiteId(null);
            $this->domain = array();


            /* Get Cloned Pages */
            $pages = $this->getPages();
            $clonedPages = array();

            /** @var \Rcm\Entity\Page $page */
            foreach ($pages as $page) {

                $pageType = $page->getPageType();

                if ($pageType != 'n' && $pageType != 'z' && $pageType != 't') {
                    continue;
                }

                $currentRevision = $page->getCurrentRevision();
                if (empty($currentRevision)) {
                    continue;
                }

                $clonedPage = clone $page;
                $clonedPage->setSite($this);
                $clonedPages[] = $clonedPage;
            }

            $this->pages = new ArrayCollection($clonedPages);

            /* Get Cloned Sitewide Plugins */
            $sitePluginInstances = $this->getRawPluginInstances();
            $clonedPluginInstances = array();

            /** @var \Rcm\Entity\PluginInstance $sitePluginInstance */
            foreach ($sitePluginInstances as $sitePluginInstance) {
                $instanceId = $sitePluginInstance->getInstanceId();
                $clonedInstance = clone $sitePluginInstance;

                $clonedPluginInstances[] = $clonedInstance;

                /** @var \Rcm\Entity\Page $page */
                foreach ($this->pages as $page) {
                    $currentRevision = $page->getCurrentRevision();

                    if (empty($currentRevision)) {
                        continue;
                    }

                    $pluginWrappers = $currentRevision->getRawPluginInstances();

                    /** @var \Rcm\Entity\PagePluginInstance $pluginWrapper */
                    foreach ($pluginWrappers as $pluginWrapper) {
                        if (!$pluginWrapper->getInstance()->isSiteWide()) {
                            continue;
                        }

                        if ($pluginWrapper->getInstance()->getInstanceId() == $instanceId) {
                            $pluginWrapper->setInstance($clonedInstance);
                        }
                    }
                }
            }

            $this->sitePlugins = new ArrayCollection($clonedPluginInstances);
            $this->pwsInfo = clone $this->pwsInfo;
            $this->pwsInfo->setPwsId(null);
        }
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
     * @param string $theme
     */
    public function setTheme($theme)
    {
        $this->theme = $theme;
    }

    /**
     * @return string
     */
    public function getTheme()
    {
        return $this->theme;
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
        $this->pages[] = $page;
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

    /**
     * @param boolean $loginRequired
     */
    public function setLoginRequired($loginRequired)
    {
        $this->loginRequired = $loginRequired;
    }

    /**
     * @return boolean
     */
    public function isLoginRequired()
    {
        return $this->loginRequired;
    }

    /**
     * @param string $loginPage
     */
    public function setLoginPage($loginPage)
    {
        $this->loginPage = $loginPage;
    }

    /**
     * @return string
     */
    public function getLoginPage()
    {
        return $this->loginPage;
    }

    public function addPermittedAccountTypesByArray(
        Array $permittedAccountTypes
    )
    {
        $types = explode(',', $this->permittedAccountTypes);
        $newTypes = array_unique(array_merge($permittedAccountTypes, $types));
        $this->permittedAccountTypes = implode(',', $newTypes);
    }

    /**
     * @param string $permittedAccountType
     */
    public function addPermittedAccountType($permittedAccountType)
    {
        $types = explode(',', $this->permittedAccountTypes);
        $types[] = $permittedAccountType;
        $this->permittedAccountTypes = implode(',', $types);
    }

    /**
     * @return string
     */
    public function getPermittedAccountTypes()
    {
        return explode(',', $this->permittedAccountTypes);
    }

    public function isPermitted($accountType)
    {
        $permitted = $this->getPermittedAccountTypes();

        if (in_array($accountType, $permitted)) {
            return true;
        }

        return false;
    }


}