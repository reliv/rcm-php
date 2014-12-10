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
class Site implements ApiInterface
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
    protected $siteTitle = null;

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
    protected $favIcon = null;

    /**
     * @var array Array of pages
     *
     * @ORM\OneToMany(
     *     targetEntity="Page",
     *     mappedBy="site",
     *     indexBy="name",
     *     cascade={"persist"}
     * )
     */
    protected $pages;

    /**
     * @var array Array of containers
     *
     * @ORM\OneToMany(
     *     targetEntity="Container",
     *     mappedBy="site",
     *     indexBy="name",
     *     cascade={"persist"}
     * )
     */
    protected $containers = null;

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
    protected $sitePlugins = [];

    /**
     * @var string URL to login page.
     *
     * @ORM\Column(type="string", nullable=true)
     **/
    protected $loginPage = 'login';

    /**
     * @var string URL to not authorized page.
     *
     * @ORM\Column(type="string", nullable=true)
     **/
    protected $notAuthorizedPage = 'not-authorized';

    /**
     * @var string URL to not authorized page.
     *
     * @ORM\Column(type="string", nullable=true)
     **/
    protected $notFoundPage = 'not-found';

    /**
     * @var array Supported page types - these should be populated at object creation
     * @todo This should be part of the DB schema, so each site can have a list on creation
     */
    protected $supportedPageTypes
        = [
            'n' => [
                'type' => 'n',
                'title' => 'Normal Page',
                'canClone' => true,
            ],
            't' => [
                'type' => 't',
                'title' => 'Template Page',
                'canClone' => true,
            ],
            'z' => [
                'type' => 'z',
                'title' => 'System Page',
                'canClone' => true,
            ],
        ];

    /**
     * Constructor for site
     */
    public function __construct()
    {
        $this->pages = new ArrayCollection();
        $this->sitePlugins = new ArrayCollection();
        $this->containers = new ArrayCollection();
        $this->domain = new Domain();
        $this->country = new Country();
        $this->language = new Language();
    }

    /**
     * __clone
     *
     * @return void
     */
    public function __clone()
    {

        if (!$this->siteId) {
            return;
        }

        $this->siteId = null;
        $this->domain = null;

        /* Clone Site Wide Plugins */
        $siteWidePlugins = $this->sitePlugins;
        $clonedSiteWides = [];
        $siteWideIdsToChange = [];

        if (!empty($siteWidePlugins)) {
            /** @var \Rcm\Entity\PluginInstance $siteWidePlugin */
            foreach ($siteWidePlugins as $siteWidePlugin) {
                $clonedSiteWide = clone $siteWidePlugin;
                $siteWideIdsToChange[$siteWidePlugin->getInstanceId()]
                    = $clonedSiteWide;
                $clonedSiteWides[] = $clonedSiteWide;
            }
        }

        /* Get Cloned Pages */
        $pages = $this->getPages();
        $clonedPages = [];

        if (!empty($pages)) {
            /** @var \Rcm\Entity\Page $page */
            foreach ($pages as $page) {

                $pageType = $page->getPageType();

                // Only clone if is supported
                if (!isset($this->supportedPageTypes[$pageType])) {
                    continue;
                }
                // Only clone if is cloneable
                if (!$this->supportedPageTypes[$pageType]['canClone']) {
                    continue;
                }

                $clonedPage = $this->getContainerClone($page, $siteWideIdsToChange);

                if (!$clonedPage) {
                    continue;
                }

                $clonedPages[] = $clonedPage;
            }

            $this->pages = new ArrayCollection($clonedPages);
        }

        /* Get Cloned Containers */
        $containers = $this->getContainers();
        $clonedContainers = [];

        if (!empty($containers)) {
            /** @var \Rcm\Entity\Container $container */
            foreach ($containers as $container) {
                $clonedContainer = $this->getContainerClone($container, $siteWideIdsToChange);

                if (!$clonedContainer) {
                    continue;
                }

                $clonedContainers[] = $clonedContainer;
            }

            $this->containers = new ArrayCollection($clonedContainers);
        }
    }

    protected function getContainerClone(ContainerInterface $original, $siteWideIdsToChange)
    {
        $clonedContainer = clone $original;
        $clonedContainer->setSite($this);
        $clonedContainer->setName($original->getName());


        $check = $original->getPublishedRevision();

        if (empty($check)) {
            return null;
        }

        $revision = $clonedContainer->getStagedRevision();

        if (empty($revision)) {
            return null;
        }

        $clonedContainer->setPublishedRevision($revision);

        $this->fixRevisionSiteWides($revision, $siteWideIdsToChange);

        return $clonedContainer;
    }

    /**
     * fixRevisionSiteWides
     *
     * @param Revision $revision
     * @param array    $siteWideIdsToChange
     *
     * @return void
     */
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
     * getSupportedPageTypes
     *
     * @return array
     */
    public function getSupportedPageTypes()
    {
        return $this->supportedPageTypes;
    }

    /**
     * setSupportedPageTypes
     *
     * @param array $supportedPageTypes
     *
     * @return void
     */
    public function setSupportedPageTypes(array $supportedPageTypes)
    {
        $this->supportedPageTypes = $supportedPageTypes;
    }

    /**
     * Add Supported Page Type
     *
     * @param array $pageType
     *
     * @return void
     */
    public function addPageType(array $pageType)
    {
        $this->supportedPageTypes[$pageType['type']] = $pageType;
    }

    /**
     * Remove Supported Page Type
     *
     * @param array $pageType
     *
     * @return void
     */
    public function removePageType(array $pageType)
    {
        unset($this->supportedPageTypes[$pageType['type']]);
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
     * getPage
     *
     * @param        $pageName
     * @param string $pageType
     *
     * @return null|Page
     */
    public function getPage($pageName, $pageType = 'n')
    {
        if (empty($this->pages)) {
            return null;
        }

        /** @var \Rcm\Entity\Page $page */
        foreach ($this->pages as $page) {
            if ($page->getName() == $pageName
                && $page->getPageType() == $pageType
            ) {
                return $page;
            }
        }

        return null;
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
        $this->pages[$page->getName()] = $page;
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
     * Get all the page entities for the site.
     *
     * @param string $name Name of container
     *
     * @return Container Container Entity
     */
    public function getContainer($name)
    {
        $container = $this->containers->get($name);

        if (empty($container)) {
            return null;
        }

        return $container;
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
        $this->containers[$container->getName()] = $container;
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
     * listAvailableSiteWidePlugins
     *
     * @return array
     */
    public function listAvailableSiteWidePlugins()
    {
        $plugins = $this->getSiteWidePlugins();

        $list = [];


        if (empty($plugins)) {
            return $list;
        }

        /** @var \Rcm\Entity\PluginInstance $plugin */
        foreach ($plugins as $plugin) {
            $list[$plugin->getDisplayName()] = [
                'displayName' => $plugin->getDisplayName(),
                'icon' => '/modules/rcm/images/GenericIcon.png',
                'siteWide' => true,
                'name' => $plugin->getPlugin(),
                'instanceId' => $plugin->getInstanceId()
            ];
        }

        return $list;
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

    /**
     * @return string
     */
    public function getNotAuthorizedPage()
    {
        return $this->notAuthorizedPage;
    }

    /**
     * @param string $notAuthorizedPage
     */
    public function setNotAuthorizedPage($notAuthorizedPage)
    {
        $this->notAuthorizedPage = $notAuthorizedPage;
    }

    /**
     * @return string
     */
    public function getNotFoundPage()
    {
        return $this->notFoundPage;
    }

    /**
     * @param string $notFoundPage
     */
    public function setNotFoundPage($notFoundPage)
    {
        $this->notFoundPage = $notFoundPage;
    }

    /**
     * getLocale
     *
     * @return string
     */
    public function getLocale()
    {
        return
            strtolower($this->getLanguage()->getIso6391())
            . '_' .
            strtoupper($this->getCountry()->getIso2());
    }

    /**
     * populate @todo some properties are missing
     *
     * @param array $data
     *
     * @return void
     */
    public function populate($data)
    {
        if (!empty($data['siteId'])) {
            $this->setSiteId($data['siteId']);
        }
        if (!empty($data['domain']) && $data['domain'] instanceof Domain) {
            $this->setDomain($data['domain']);
        }
        if (!empty($data['domain']) && is_array($data['domain'])) {
            // is this right?
            $domain = new Domain();
            $domain->populate($data['domain']);
            $this->setDomain($domain);
        }
        if (!empty($data['theme'])) {
            $this->setTheme($data['theme']);
        }
        if (!empty($data['siteLayout'])) {
            $this->setSiteLayout($data['siteLayout']);
        }
        if (!empty($data['siteTitle'])) {
            $this->setSiteTitle($data['siteTitle']);
        }
        if (!empty($data['language']) && $data['language'] instanceof Language) {
            $this->setLanguage($data['language']);
        }
        if (!empty($data['language']) && is_array($data['language'])) {
            $language = new Language();
            $language->populate($data['language']);
            $this->setLanguage($language);
        }
        if (!empty($data['country']) && $data['country'] instanceof Country) {
            $this->setCountry($data['country']);
        }
        if (!empty($data['country']) && is_array($data['country'])) {
            $country = new Country();
            $country->populate($data['country']);
            $this->setCountry($country);
        }
        if (!empty($data['status'])) {
            $this->setStatus($data['status']);
        }
        if (!empty($data['favIcon'])) {
            $this->setFavIcon($data['favIcon']);
        }
        if (!empty($data['loginPage'])) {
            $this->setLoginPage($data['loginPage']);
        }
        if (!empty($data['notAuthorizedPage'])) {
            $this->setNotAuthorizedPage($data['notAuthorizedPage']);
        }
        if (!empty($data['notFoundPage'])) {
            $this->setNotFoundPage($data['notFoundPage']);
        }
        if (!empty($data['supportedPageTypes'])) {
            $this->setSupportedPageTypes($data['supportedPageTypes']);
        }
    }

    /**
     * populateFromObject - @todo some properties are missing
     *
     * @param Site|ApiInterface $object
     *
     * @return void
     */
    public function populateFromObject(ApiInterface $object)
    {
        if (!$object instanceof Site) {
            return;
        }
        $this->setSiteId($object->getSiteId());
        if (is_object($object->getDomain())) {
            $this->setDomain($object->getDomain());
        }
        $this->setTheme($object->getTheme());
        $this->setSiteLayout($object->getSiteLayout());
        $this->setSiteTitle($object->getSiteTitle());
        if (is_object($object->getLanguage())) {
            $this->setLanguage($object->getLanguage());
        }
        if (is_object($object->getCountry())) {
            $this->setCountry($object->getCountry());
        }
        $this->setStatus($object->getStatus());
        $this->setFavIcon($object->getFavIcon());
        $this->setLoginPage($object->getLoginPage());
        $this->setNotAuthorizedPage($object->getNotAuthorizedPage());
        $this->setNotFoundPage($object->getNotFoundPage());
        $this->setSupportedPageTypes($object->getSupportedPageTypes());
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
}
