<?php
/**
 * Site Information Entity
 *
 * This is a Doctrine 2 definition file for Site info.  This file
 * is used for any module that needs to know site information.
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

/**
 * Site Information Entity
 *
 * This object contains a list of layouts for use with the content managment
 * system.
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 *
 * @ORM\Entity (repositoryClass="Rcm\Repository\Site")
 * @ORM\Table(name="rcm_sites")
 *
 * @SuppressWarnings(PHPMD)
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
     * @var \Rcm\Entity\Domain Primary Domain name for a site.
     *
     * @ORM\OneToOne(targetEntity="Domain")
     * @ORM\JoinColumn(
     *     name="domainId",
     *     referencedColumnName="domainId",
     *     onDelete="SET NULL"
     * )
     */
    protected $domain;

    /**
     * @var string Theme of site
     *
     * @ORM\Column(type="string")
     */
    protected $theme;

    /**
     * @var string Default Site Layout
     *
     * @ORM\Column(type="string")
     */
    protected $siteLayout;

    /**
     * @var string Default Site Title for all pages
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $siteTitle;

    /**
     * @var \Rcm\Entity\Language Default language for the site
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
     * @ORM\JoinColumn(
     *     name="country",
     *     referencedColumnName="iso3",
     *     onDelete="SET NULL"
     * )
     */
    protected $country;

    /**
     * @var string Status of site.
     *
     * @ORM\Column(type="string", length=2)
     */
    protected $status;

    /**
     * @var string Meta Keywords
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $favIcon;

    /**
     * @var array Array of pages
     *
     * @ORM\OneToMany(
     *     targetEntity="Page",
     *     mappedBy="site"
     * )
     */
    protected $pages;

    /**
     * @var array Array of containers
     *
     * @ORM\OneToMany(
     *     targetEntity="Container",
     *     mappedBy="site"
     * )
     */
    protected $containers;

    /**
     * @ORM\ManyToMany(
     *     targetEntity="PluginInstance"
     * )
     * @ORM\JoinTable(
     *     name="rcm_site_plugin_instances",
     *     joinColumns={
     *         @ORM\JoinColumn(
     *             name="siteId",
     *             referencedColumnName="siteId",
     *             onDelete="CASCADE"
     *         )
     *     },
     *     inverseJoinColumns={
     *         @ORM\JoinColumn(
     *             name="pluginInstanceId",
     *             referencedColumnName="pluginInstanceId",
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
     * @var string Comma separated list of ACL roles permitted
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $aclRoles;

    /**
     * Constructor for site
     */
    public function __construct()
    {
        $this->pages = new ArrayCollection();
        $this->sitePlugins = new ArrayCollection();
        $this->containers = new ArrayCollection();
        $this->domain = new ArrayCollection();
    }

    public function __clone()
    {
        $this->siteId = null;
        $this->domain = null;

        /* Clone Site Wide Plugins */
        $siteWidePlugins = $this->sitePlugins;
        $clonedSiteWides = array();
        $siteWideIdsToChange = array();

        if (!empty($siteWidePlugins)) {
            /** @var \Rcm\Entity\PluginInstance $siteWidePlugin */
            foreach ($siteWidePlugins as $siteWidePlugin) {
                $clonedSiteWide = clone $siteWidePlugin;
                $siteWideIdsToChange[$siteWidePlugin->getInstanceId()] = $clonedSiteWide;
                $clonedSiteWides[] = $clonedSiteWide;
            }
        }

        /* Get Cloned Pages */
        $pages = $this->getPages();
        $clonedPages = array();

        if (!empty($pages)) {
            /** @var \Rcm\Entity\Page $page */
            foreach ($pages as $page) {

                $pageType = $page->getPageType();

                if ($pageType != 'n' && $pageType != 'z' && $pageType != 't') {
                    continue;
                }

                $clonedPage = clone $page;
                $clonedPage->setSite($this);
                $clonedPages[] = $clonedPage;

                $revision = $clonedPage->getCurrentRevision();

                if (empty($revision)) {
                    continue;
                }

                $this->fixRevisionSiteWides($revision, $siteWideIdsToChange);
            }

            $this->pages = new ArrayCollection($clonedPages);
        }

        /* Get Cloned Containers */
        $containers = $this->getContainers();
        $clonedContainers = array();

        if (!empty($containers)) {
            /** @var \Rcm\Entity\Container $container */
            foreach ($containers as $container) {

                $clonedContainer = clone $container;
                $clonedContainer->setSite($this);
                $clonedContainers[] = $clonedContainer;

                $revision = $clonedContainer->getCurrentRevision();

                if (empty($revision)) {
                    continue;
                }

                $this->fixRevisionSiteWides($revision, $siteWideIdsToChange);
            }

            $this->containers = new ArrayCollection($clonedContainers);
        }


    }

    protected function fixRevisionSiteWides(Revision $revision, $siteWideIdsToChange)
    {
        $pluginWrappers = $revision->getPluginWrappers();

        /** @var \Rcm\Entity\PluginWrapper $pluginWrapper */
        foreach ($pluginWrappers as $pluginWrapper) {
            $instanceId = $pluginWrapper->getInstance()->getInstanceId();

            if (isset($siteWideIdsToChange[$instanceId])
                && $siteWideIdsToChange[$instanceId] instanceof PluginInstance
            ) {
                $pluginWrapper->setInstance($siteWideIdsToChange[$instanceId]);
            }
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
     * @return void
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
     * @return void
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
     * @param Domain $domain Domain object to add
     *
     * @return void
     */
    public function setDomain(Domain $domain)
    {
        $this->domain = $domain;
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
     * @param Language $language Language Entity
     *
     * @return void
     */
    public function setLanguage(Language $language)
    {
        $this->language = $language;
    }

    /**
     * Gets the Country property
     *
     * @return Country Country
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
    public function setCountry(Country $country)
    {
        $this->country = $country;
    }

    /**
     * Set the theme to be used by the site
     *
     * @param string $theme RCM Theme Path
     *
     * @return void
     */
    public function setTheme($theme)
    {
        $this->theme = $theme;
    }

    /**
     * Get the theme used by the site
     *
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
     * @return void
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Get all the page entities for the site.
     *
     * @return ArrayCollection
     */
    public function getPages()
    {
        return $this->pages;
    }

    /**
     * Set up a page
     *
     * @param Page $page Page Entity to add.
     *
     * @return void
     */
    public function addPage(Page $page)
    {
        $this->pages[] = $page;
    }

    /**
     * Remove a page from the site
     *
     * @param Page $page Page Entity to remove from list
     *
     * @return void
     */
    public function removePage(Page $page)
    {
        $this->pages->removeElement($page);
    }

    /**
     * Get all the page entities for the site.
     *
     * @return ArrayCollection Array of page entities
     */
    public function getContainers()
    {
        return $this->containers;
    }

    /**
     * Set up a page
     *
     * @param Container $container Page Entity to add.
     *
     * @return void
     */
    public function addContainer(Container $container)
    {
        $this->containers[] = $container;
    }

    /**
     * Remove a page from the site
     *
     * @param Container $container Page Entity to remove.
     *
     * @return void
     */
    public function removeContainer(Container $container)
    {
        $this->containers->removeElement($container);
    }

    /**
     * Get Site wide plugins
     *
     * @return ArrayCollection Returns an array collection of PluginInstance Entities
     */
    public function getSiteWidePlugins()
    {
        return $this->sitePlugins;
    }

    /**
     * Add a plugin to the site.
     *
     * @param PluginInstance $plugin Site wide plugin.
     *
     * @return null
     * @throws InvalidArgumentException
     */
    public function addSiteWidePlugin(PluginInstance $plugin)
    {
        if (!$plugin->isSiteWide()) {
            throw new InvalidArgumentException(
                'Plugin Instance Must be set to Site Wide'
            );
        }

        $displayName = $plugin->getDisplayName();

        if (empty($displayName)) {
            throw new InvalidArgumentException(
                'Plugin Instance Must be set to Site Wide'
            );
        }

        $this->sitePlugins->add($plugin);
    }

    /**
     * Remove a Site Wide Plugin Instance from the entity
     *
     * @param PluginInstance $plugin Site wide plugin.
     *
     * @return void
     */
    public function removeSiteWidePlugin(PluginInstance $plugin)
    {
        $this->sitePlugins->removeElement($plugin);
    }

    /**
     * Set Fav Icon for site.  This is needed when rendering pages outside the
     * CMS.
     *
     * @param string $favIcon Path to FavIcon
     *
     * @return void
     */
    public function setFavIcon($favIcon)
    {
        $this->favIcon = $favIcon;
    }

    /**
     * Get Site Favicon
     *
     * @return string
     */
    public function getFavIcon()
    {
        return $this->favIcon;
    }

    /**
     * Set the site title for the site
     *
     * @param string $title Title for the site
     *
     * @return void
     */
    public function setSiteTitle($title)
    {
        $this->siteTitle = $title;
    }

    /**
     * Get the sites title
     *
     * @return string
     */
    public function getSiteTitle()
    {
        return $this->siteTitle;
    }

    /**
     * Set login required for the whole site
     *
     * @param boolean $loginRequired Login Required
     *
     * @return void
     */
    public function setLoginRequired($loginRequired)
    {
        $this->loginRequired = $loginRequired;
    }

    /**
     * Is login required?
     *
     * @return boolean
     */
    public function isLoginRequired()
    {
        return $this->loginRequired;
    }

    /**
     * Path to login page.  Because the login page can be variable the site
     * needs to keep a reference to the login page.
     *
     * @param string $loginPage Login Page
     *
     * @return void
     */
    public function setLoginPage($loginPage)
    {
        $this->loginPage = $loginPage;
    }

    /**
     * Get path to login page
     *
     * @return string
     */
    public function getLoginPage()
    {
        return $this->loginPage;
    }

    /**
     * Add an ACL role to the allowed list.
     *
     * @param string|array $permittedRoles Comma separated list or array
     *                                     of allowed ACL Roles
     *
     * @return void
     */
    public function addAclRoles($permittedRoles)
    {

        if (!is_array($permittedRoles)) {
            $permittedRoles = explode(
                ',',
                rtrim($permittedRoles, ',')
            );

            $permittedRoles = array_map('trim', $permittedRoles);
        }

        if (!empty($this->aclRoles)) {
            $types = explode(
                ',',
                $this->aclRoles
            );

            $permittedRoles = array_unique(
                array_merge($types, $permittedRoles)
            );
        }

        $this->aclRoles = rtrim(implode(',', $permittedRoles), ',');
    }

    /**
     * Get an array of permitted account types
     *
     * @return string
     */
    public function getAclRoles()
    {
        return explode(',', $this->aclRoles);
    }

    /**
     * Check to see if ACL role is already allowed.  This should not be used to
     * check if a user is allowed.  To check if a current user has permissions
     * please check against ACL directly.
     *
     * @param string $aclRole ACL Role to Check
     *
     * @return bool
     */
    public function hasRole($aclRole)
    {
        $permitted = $this->getAclRoles();

        if (in_array($aclRole, $permitted)) {
            return true;
        }

        return false;
    }

    /**
     * @param string $siteLayout
     */
    public function setSiteLayout($siteLayout)
    {
        $this->siteLayout = $siteLayout;
    }

    /**
     * @return string
     */
    public function getSiteLayout()
    {
        return $this->siteLayout;
    }
}
