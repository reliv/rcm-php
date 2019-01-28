<?php

namespace Rcm\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Rcm\Exception\InvalidArgumentException;
use Rcm\Page\PageTypes\PageTypes;
use Rcm\Tracking\Exception\TrackingException;
use Rcm\Tracking\Model\Tracking;
use Reliv\RcmApiLib\Model\ApiPopulatableInterface;

/**
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 *
 * @ORM\Entity (repositoryClass="Rcm\Repository\Site")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="rcm_sites")
 *
 * @SuppressWarnings(PHPMD)
 */
class Site extends ApiModelTrackingAbstract implements \IteratorAggregate, Tracking
{
    const STATUS_ACTIVE = 'A';
    const DEFAULT_LAYOUT = 'default';

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
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $domainId;

    /**
     * @var string Theme of site
     *
     * @ORM\Column(type="string", nullable=true)
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
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $languageId;

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
     * @var ArrayCollection of pages
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
     * @var ArrayCollection of containers
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
     * @var ArrayCollection
     *
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
     * @deprecated This sometimes-inaccurate system was replaced by the immutably history system
     * @var \DateTime Date object was first created
     *
     */
    protected $createdDate;

    /**
     * @deprecated This sometimes-inaccurate system was replaced by the immutably history system
     *
     */
    protected $createdByUserId;

    /**
     * @deprecated This sometimes-inaccurate system was replaced by the immutably history system
     *
     */
    protected $createdReason = Tracking::UNKNOWN_REASON;

    /**
     * @deprecated This sometimes-inaccurate system was replaced by the immutably history system
     *
     * @var \DateTime Date object was modified
     **/
    protected $modifiedDate;

    /**
     * @deprecated This sometimes-inaccurate system was replaced by the immutably history system
     *
     * @var string User ID of modifier
     */
    protected $modifiedByUserId;

    /**
     * @deprecated This sometimes-inaccurate system was replaced by the immutably history system
     *
     * @var string Short description of create reason
     */
    protected $modifiedReason = Tracking::UNKNOWN_REASON;

    /**
     * @var array Supported page types - these should be populated at object creation
     * @todo This should be part of the DB schema, so each site can have a list on creation
     * @todo Get these from PageTypes service
     */
    protected $supportedPageTypes
        = [
            PageTypes::NORMAL => [
                'type' => PageTypes::NORMAL,
                'title' => 'Normal Page',
                'canClone' => true,
            ],
            PageTypes::TEMPLATE => [
                'type' => PageTypes::TEMPLATE,
                'title' => 'Template Page',
                'canClone' => true,
            ],
            PageTypes::SYSTEM => [
                'type' => PageTypes::SYSTEM,
                'title' => 'System Page',
                'canClone' => true,
            ],
        ];

    /**
     * @param string $createdByUserId <tracking>
     * @param string $createdReason <tracking>
     */
    /**
     * @param string $createdByUserId
     * @param string $createdReason
     * @param Domain|null $domain
     * @param Country|null $country
     * @param Language|null $language
     */
    public function __construct(
        string $createdByUserId,
        string $createdReason = Tracking::UNKNOWN_REASON,
        $domain = null,
        $country = null,
        $language = null
    ) {
        $this->pages = new ArrayCollection();
        $this->sitePlugins = new ArrayCollection();
        $this->containers = new ArrayCollection();

        // Removed this because it is dangerous
        if ($domain instanceof Domain) {
            $this->setDomain($domain);
        }

        // Removed this because it is dangerous
        if ($country instanceof Country) {
            $this->setCountry($country);
        }

        // Removed this because it is dangerous
        if ($language instanceof Language) {
            $this->setLanguage($language);
        }

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
        string $createdReason = Tracking::UNKNOWN_REASON,
        $copyPages = true
    ) {
        /** @var static $new */
        $new = parent::newInstance(
            $createdByUserId,
            $createdReason
        );

        // if no id, then it has not been save and can be returned
        if (empty($new->siteId)) {
            return $new;
        }

        $new->siteId = null;
        $new->domain = null;

        if ($copyPages) {
            /* Get Cloned Pages */
            $pages = $new->getPages();

            $clonedPages = [];

            /** @var \Rcm\Entity\Page $page */
            foreach ($pages as $page) {
                $pageType = $page->getPageType();

                // Only clone if is supported
                if (!isset($new->supportedPageTypes[$pageType])) {
                    continue;
                }
                // Only clone if is cloneable
                if (!$new->supportedPageTypes[$pageType]['canClone']) {
                    continue;
                }

                $clonedPage = $page->newInstanceIfHasRevision(
                    $createdByUserId,
                    $createdReason
                );

                if (!$clonedPage) {
                    continue;
                }

                $clonedPage->setSite($new);

                $clonedPages[] = $clonedPage;
            }

            $new->pages = new ArrayCollection($clonedPages);
        }

        /* Get Cloned Containers */
        $containers = $this->getContainers();
        $clonedContainers = [];

        /** @var \Rcm\Entity\Container $container */
        foreach ($containers as $container) {
            $clonedContainer = $container->newInstanceIfHasRevision(
                $createdByUserId,
                $createdReason
            );

            if (!$clonedContainer) {
                continue;
            }

            $clonedContainer->setSite($new);

            $clonedContainers[] = $clonedContainer;
        }

        $new->containers = new ArrayCollection($clonedContainers);

        return $new;
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
        $this->domainId = $domain->getDomainId();
    }

    /**
     * getDomainId
     *
     * @return int|null
     */
    public function getDomainId()
    {
        return $this->domainId;
    }

    /**
     * getDomainName
     *
     * @return null|string
     */
    public function getDomainName()
    {
        if ($this->domain) {
            return $this->domain->getDomainName();
        }

        return null;
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
        $this->languageId = $language->getLanguageId();
    }

    /**
     * getLanguageId
     *
     * @return int|null
     */
    public function getLanguageId()
    {
        return $this->languageId;
    }

    /**
     * getLanguageIso6392t
     *
     * @return null|string
     */
    public function getLanguageIso6392t()
    {
        if ($this->language) {
            return $this->language->getIso6392t();
        }

        return null;
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
     * getCountryId
     *
     * @return int|null
     */
    public function getCountryId()
    {
        if ($this->country) {
            return $this->country->getIso3();
        }

        return null;
    }

    /**
     * @return null|string
     */
    public function getCountryIso3()
    {
        if ($this->country) {
            return $this->country->getIso3();
        }

        return null;
    }

    /**
     * Sets the Country property
     *
     * @param \Rcm\Entity\Country $country Country Entity
     *
     * @return void
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
     * isActive
     *
     * @return string
     */
    public function isActive()
    {
        $status = $this->getStatus();

        return $status === self::STATUS_ACTIVE;
    }

    /**
     * isSiteAvailable
     *
     * @return bool
     */
    public function isSiteAvailable()
    {
        $siteId = $this->getSiteId();

        return (!empty($siteId) && $this->isActive());
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
    public function getPage($pageName, $pageType = PageTypes::NORMAL)
    {
        if ($this->pages->count() < 1) {
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
        $this->pages->set($page->getName(), $page);
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
     * @deprecated <deprecated-site-wide-plugin>
     * Get Site wide plugins
     *
     * @return ArrayCollection Returns an array collection of PluginInstance Entities
     */
    public function getSiteWidePlugins()
    {
        return $this->sitePlugins;
    }

    /**
     * @deprecated <deprecated-site-wide-plugin>
     * Add a plugin to the site.
     *
     * @param PluginInstance $plugin Site wide plugin.
     *
     * @return void
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
     * @deprecated <deprecated-site-wide-plugin>
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
                'icon' => $plugin->getIcon(),
                'siteWide' => true, // @deprecated <deprecated-site-wide-plugin>
                'name' => $plugin->getPlugin(),
                'instanceId' => $plugin->getInstanceId()
            ];
        }

        return $list;
    }

    /**
     * @deprecated <deprecated-site-wide-plugin>
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
     *
     * @return string
     */
    public function getLocale()
    {
        $language = $this->getLanguage();
        $country = $this->getCountry();

        if (empty($language) || empty($country)) {
            return null;
        }

        return
            strtolower($language->getIso6391())
            . '_' .
            strtoupper($country->getIso2());
    }

    /**
     * populate
     *
     * @todo some properties are missing
     * @todo $data['createdByUserId'] should
     *
     * @param array $data
     * @param array $ignore
     *
     * @return void
     * @throws TrackingException
     */
    public function populate(array $data, array $ignore = ['createdByUserId', 'createdDate', 'createdReason'])
    {
        if (!empty($data['siteId']) && !in_array('siteId', $ignore)) {
            $this->setSiteId($data['siteId']);
        }

        // Domain
        if (!empty($data['domain']) && $data['domain'] instanceof Domain
            && !in_array('domain', $ignore)
        ) {
            $this->setDomain($data['domain']);
            unset($data['domain']);
        }

        if (!empty($data['domain']) && empty($data['createdByUserId'])) {
            throw new TrackingException('Populating new domain requires createdByUserId');
        }

        if (!empty($data['domain']) && is_array($data['domain'])
            && !in_array('domain', $ignore)
        ) {
            // @todo This is dangerous
            $domain = new Domain(
                $data['createdByUserId'],
                'New domain on populate in ' . get_class($this)
            );
            $domain->populate($data['domain']);
            $this->setDomain($domain);
        }

        if (!empty($data['theme']) && !in_array('theme', $ignore)) {
            $this->setTheme($data['theme']);
        }
        if (!empty($data['siteLayout']) && !in_array('siteLayout', $ignore)) {
            $this->setSiteLayout($data['siteLayout']);
        }
        if (!empty($data['siteTitle']) && !in_array('siteTitle', $ignore)) {
            $this->setSiteTitle($data['siteTitle']);
        }

        // Language
        if (!empty($data['language']) && $data['language'] instanceof Language
            && !in_array('language', $ignore)
        ) {
            $this->setLanguage($data['language']);
            unset($data['language']);
        }

        if (!empty($data['language']) && empty($data['createdByUserId'])) {
            throw new TrackingException('Populating new language requires createdByUserId');
        }

        if (!empty($data['language']) && is_array($data['language'])
            && !in_array(
                'language',
                $ignore
            )
        ) {
            // @todo This is dangerous
            $language = new Language(
                $data['createdByUserId'],
                'New language on populate in ' . get_class($this)
            );
            $language->populate($data['language']);
            $this->setLanguage($language);
        }

        // Country
        if (!empty($data['country']) && $data['country'] instanceof Country
            && !in_array('country', $ignore)
        ) {
            $this->setCountry($data['country']);
            unset($data['country']);
        }

        if (!empty($data['country']) && empty($data['createdByUserId'])) {
            throw new TrackingException('Populating new country requires createdByUserId');
        }

        if (!empty($data['country']) && is_array($data['country'])
            && !in_array(
                'country',
                $ignore
            )
        ) {
            // @todo This is dangerous
            $country = new Country(
                $data['createdByUserId'],
                'New country on populate in ' . get_class($this)
            );
            $country->populate($data['country']);
            $this->setCountry($country);
        }
        if (!empty($data['status']) && !in_array('status', $ignore)) {
            $this->setStatus($data['status']);
        }
        if (!empty($data['favIcon']) && !in_array('favIcon', $ignore)) {
            $this->setFavIcon($data['favIcon']);
        }
        if (!empty($data['loginPage']) && !in_array('loginPage', $ignore)) {
            $this->setLoginPage($data['loginPage']);
        }
        if (!empty($data['notAuthorizedPage'])
            && !in_array(
                'notAuthorizedPage',
                $ignore
            )
        ) {
            $this->setNotAuthorizedPage($data['notAuthorizedPage']);
        }
        if (!empty($data['notFoundPage']) && !in_array('notFoundPage', $ignore)) {
            $this->setNotFoundPage($data['notFoundPage']);
        }
        if (!empty($data['supportedPageTypes'])
            && !in_array(
                'supportedPageTypes',
                $ignore
            )
        ) {
            $this->setSupportedPageTypes($data['supportedPageTypes']);
        }
    }

    /**
     * populateFromObject - @todo some properties are missing
     *
     * @param ApiPopulatableInterface $object
     * @param array $ignore
     *
     * @return void
     */
    public function populateFromObject(
        ApiPopulatableInterface $object,
        array $ignore = []
    ) {
        if (!$object instanceof Site) {
            return;
        }
        if (!in_array('siteId', $ignore)) {
            $this->setSiteId($object->getSiteId());
        }
        if (is_object($object->getDomain()) && !in_array('domain', $ignore)) {
            $this->setDomain($object->getDomain());
        }
        if (!in_array('theme', $ignore)) {
            $this->setTheme($object->getTheme());
        }
        if (!in_array('siteLayout', $ignore)) {
            $this->setSiteLayout($object->getSiteLayout());
        }
        if (!in_array('siteTitle', $ignore)) {
            $this->setSiteTitle($object->getSiteTitle());
        }
        if (is_object($object->getLanguage()) && !in_array('language', $ignore)) {
            $this->setLanguage($object->getLanguage());
        }
        if (is_object($object->getCountry()) && !in_array('country', $ignore)) {
            $this->setCountry($object->getCountry());
        }
        if (!in_array('status', $ignore)) {
            $this->setStatus($object->getStatus());
        }
        if (!in_array('favIcon', $ignore)) {
            $this->setFavIcon($object->getFavIcon());
        }
        if (!in_array('loginPage', $ignore)) {
            $this->setLoginPage($object->getLoginPage());
        }
        if (!in_array('notAuthorizedPage', $ignore)) {
            $this->setNotAuthorizedPage($object->getNotAuthorizedPage());
        }
        if (!in_array('notFoundPage', $ignore)) {
            $this->setNotFoundPage($object->getNotFoundPage());
        }
        if (!in_array('supportedPageTypes', $ignore)) {
            $this->setSupportedPageTypes($object->getSupportedPageTypes());
        }
    }

    /**
     * jsonSerialize
     *
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return $this->toArray(['pages', 'containers', 'sitePlugins']);
    }

    /**
     * getIterator
     *
     * @return array|\Traversable
     */
    public function getIterator()
    {
        return new \ArrayIterator(
            $this->toArray(['pages', 'containers', 'sitePlugins'])
        );
    }

    /**
     * toArray
     *
     * @param array $ignore
     *
     * @return mixed
     */
    public function toArray($ignore = ['pages', 'containers', 'sitePlugins'])
    {
        $data = parent::toArray($ignore);

        if (!in_array('pages', $ignore)) {
            $data['pages'] = $this->modelArrayToArray(
                $this->getPages()->toArray(),
                ['parent', 'site', 'revisions']
            );
        }

        if (!in_array('containers', $ignore)) {
            $data['containers'] = $this->modelArrayToArray(
                $this->getContainers()->toArray(),
                ['parent', 'site', 'revisions']
            );
        }

        if (!in_array('domainId', $ignore)) {
            $data['domainId'] = $this->getDomainId();
        }
        if (!in_array('domainName', $ignore)) {
            $data['domainName'] = $this->getDomainName();
        }
        if (!in_array('languageId', $ignore)) {
            $data['languageId'] = $this->getLanguageId();
        }
        if (!in_array('languageIso6392t', $ignore)) {
            $data['languageIso6392t'] = $this->getLanguageIso6392t();
        }
        if (!in_array('countryId', $ignore)) {
            $data['countryId'] = $this->getCountryId();
        }
        if (!in_array('locale', $ignore)) {
            $data['locale'] = $this->getLocale();
        }

        return $data;
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
