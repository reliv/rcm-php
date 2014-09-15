<?php
/**
 * Rcm Site Manager
 *
 * This file contains the class definition for the Site Manager
 *
 * PHP version 5.3
 *
 * LICENSE: BSD
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://github.com/reliv
 */

namespace Rcm\Service;

use Doctrine\ORM\EntityRepository;
use Rcm\Entity\Country;
use Rcm\Entity\Language;
use Rcm\Exception\InvalidArgumentException;
use Rcm\Exception\SiteNotFoundException;
use Zend\Cache\Storage\StorageInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\RequestInterface;

/**
 * Rcm Site Manager
 *
 * Rcm Site Manager.  This class manages sites used by the CMS.  In addition it can
 * also get the current sites information.  We use the site manager instead of
 * site entities for performance reasons.  We found that due to all the relations
 * of a site entity and the inability to cache doctrine entities as a site got
 * bigger the slower the site became.  To fix these issues we are now pulling just
 * the needed data from the database and caching the results for future request.
 * This has greatly improved the performance of the CMS when caching is enabled.
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 */
class SiteManager
{
    /*
     * Properties
     */

    /** @var integer */
    protected $currentSiteId;

    /** @var array */
    protected $siteInfo = array();

    /** @var Country|array */
    protected $currentSiteCountry;

    /** @var Language|array */
    protected $currentSiteLanguage;

    protected $domain;

    /*
     * Required Services
     */

    /** @var \Rcm\Repository\Site */
    protected $siteRepo;

    /*
     * Additional Services
     */

    /** @var \Rcm\Service\PageManager */
    protected $pageManager;

    /** @var \Rcm\Service\ContainerManager */
    protected $containerManager;

    /** @var \Rcm\Service\DomainManager */
    protected $domainManager;

    /** @var \Rcm\Service\LayoutManager */
    protected $layoutManager;

    /** @var \Rcm\Service\PluginManager */
    protected $pluginManager;

    /** @var \Zend\Cache\Storage\StorageInterface */
    protected $cache;


    /**
     * Constructor
     *
     * @param EntityRepository $repository Site Repository
     */
    public function __construct(
        EntityRepository $repository
    ) {
        $this->siteRepo = $repository;
    }

    /**
     * Get the current sites unique id
     *
     * @return integer siteId
     */
    public function getCurrentSiteId()
    {
        return $this->currentSiteId;
    }

    /**
     * Alias of Get Site Info
     *
     * @return array
     */
    public function getCurrentSiteInfo()
    {
        return $this->getSiteInfo();
    }

    /**
     * Alias of Get Site Login Page
     *
     * @return string Path to login page
     */
    public function getCurrentSiteLoginPage()
    {
        return $this->getSiteLoginPage();
    }

    /**
     * Alias of getSiteTheme()
     *
     * @return string Theme
     */
    public function getCurrentSiteTheme()
    {
        return $this->getSiteTheme();
    }

    /**
     * Alias of getSiteDefaultLayout()
     *
     * @return string
     */
    public function getCurrentSiteDefaultLayout()
    {
        return $this->getSiteDefaultLayout();
    }

    /**
     * Get Current Site Id From Domain
     *
     * @param string $domain Domain to search by
     *
     * @return integer|null                         SiteId
     * @throws \Rcm\Exception\SiteNotFoundException
     */
    public function setSiteIdFromDomain($domain)
    {
        $domainInfo = $this->domainManager->getDomainInfo($domain);

        if (empty($domainInfo)) {
            throw new SiteNotFoundException(
                'No site found for request domain: ' . $domain
            );
        }

        $this->currentSiteId = $domainInfo['siteId'];
        $this->domain = $domain;
        return;
    }

    /**
     * Get the sites information
     *
     * @return string
     * @throws InvalidArgumentException
     */
    public function getSiteInfo()
    {
        $siteId = $this->getCurrentSiteId();

        if (!empty($this->siteInfo)) {
            return $this->siteInfo;
        }

        $cacheKey = 'rcm_site_info_' . $siteId;

        if ($this->cache->hasItem($cacheKey)) {
            $this->siteInfo = $this->cache->getItem($cacheKey);
            return $this->siteInfo;
        }

        $siteInfo = $this->siteRepo->getSiteInfo($siteId);

        if (empty($siteInfo)) {
            throw new InvalidArgumentException('Invalid Site ID');
        }

        $this->siteInfo = $siteInfo;

        $this->cache->setItem($cacheKey, $siteInfo);

        return $siteInfo;
    }

    /**
     * Get the sites Login Page
     *
     * @return string
     */
    public function getSiteLoginPage()
    {
        $siteInfo = $this->getSiteInfo();
        return $siteInfo['loginPage'];
    }

    /**
     * Get the sites theme
     *
     * @return string
     * @throws InvalidArgumentException
     */
    public function getSiteTheme()
    {
        $siteInfo = $this->getSiteInfo();
        return $siteInfo['theme'];
    }

   /**
     * Get the Sites default Zend Framework Layout template.
     *
     * @return string
     */
    public function getSiteDefaultLayout()
    {
        $siteInfo = $this->getSiteInfo();
        return $siteInfo['siteLayout'];
    }


    /**
     * Get the current sites related country entity
     *
     * @return Country
     */
    public function getCurrentSiteCountry()
    {
        // Used when site data is from cache and not the db
        if (empty($this->currentSiteCountry)
            || !is_a($this->currentSiteCountry, '\Rcm\Entity\Country')
        ) {
            $siteInfo = $this->getCurrentSiteInfo();

            $country = new Country();
            $country->setCountryName($siteInfo['country']['countryName']);
            $country->setIso2($siteInfo['country']['iso2']);
            $country->setIso3($siteInfo['country']['iso3']);

            $this->currentSiteCountry = $country;
        }

        return $this->currentSiteCountry;

    }

    /**
     * Get the currents site Language Entity
     *
     * @return Language
     */
    public function getCurrentSiteLanguage()
    {
        // Used when site data is from cache and not the db
        if (empty($this->currentSiteLanguage)
            || !is_a($this->currentSiteLanguage, '\Rcm\Entity\Language')
        ) {
            $siteInfo = $this->getCurrentSiteInfo();

            $language = new Language();
            $language->setIso6391($siteInfo['language']['iso639_1']);
            $language->setIso6392b($siteInfo['language']['iso639_2b']);
            $language->setIso6392t($siteInfo['language']['iso639_2t']);
            $language->setOldWebLanguage($siteInfo['language']['iso639_2t']);
            $language->setLanguageName($siteInfo['language']['languageName']);

            $this->currentSiteLanguage = $language;
        }

        return $this->currentSiteLanguage;
    }

    /**
     * Returns php compatible locale string
     *
     * @return string
     */
    public function getCurrentSiteLocale()
    {
        $siteInfo = $this->getCurrentSiteInfo();
        return $siteInfo['language']['iso639_1']
        . '_' . $siteInfo['country']['iso2'];
    }

    /**
     * Get an array of active site objects
     *
     * @return array
     */
    public function getAllActiveSites()
    {
        return $this->siteRepo->getAllActiveSites();
    }

    /**
     * Get a site entity by id
     *
     * @param integer $siteId Site Id
     *
     * @return \Rcm\Entity\Site|null
     */
    public function getSiteById($siteId)
    {
        if ($this->isValidSiteId($siteId)) {
            return $this->siteRepo->findOneBy(
                array('siteId' => $siteId)
            );
        }
        return null;
    }

    /**
     * Is Site valid
     *
     * @param integer $siteId Site Id
     *
     * @return bool
     */
    public function isValidSiteId($siteId)
    {
        return $this->siteRepo->isValidSiteId($siteId);
    }

    /**
     * Gets list of siteWide plugins as an array
     * @return array
     */
    public function listAvailableSiteWidePlugins()
    {
        $result = $this->siteRepo->getSiteWidePluginsList($this->getCurrentSiteId());

        $list = array();

        if (empty($result)) {
            return $list;
        }

        foreach ($result as $plugin) {
            $list[$plugin['displayName']] = [
                'name' => $plugin['displayName'],
                'icon' => '/modules/rcm/images/GenericIcon.png',
                'siteWide' => true,
                'displayName' => $plugin['plugin'],
                'instanceId' => $plugin['pluginInstanceId']
            ];
        }

        return $list;
    }

    /**
     * Getter for CurrentSiteId
     *
     * @param int $currentSiteId CurrentSiteId
     *
     * @return null
     */
    public function setCurrentSiteId($currentSiteId)
    {
        $this->currentSiteId = $currentSiteId;
    }

    public function savePage(
        $pageName,
        $pageRevision,
        $pageType='n',
        $saveData
    ) {
        $siteId = $this->getCurrentSiteId();

        $this->prepSaveData($saveData);

        print_r($saveData);
        exit;

        return $saveData;

    }

    protected function prepSaveData(&$data)
    {
        $data['containers'] = array();
        $data['pageContainer'] = array();

        foreach ($data['plugins'] as &$plugin)
        {
            $this->cleanSaveData($plugin['saveData']);

            if ($plugin['containerType'] == 'layout') {
                $data['containers'][$plugin['containerId']][] = &$plugin;
            } else {
                $data['pageContainer'][$plugin['containerId']][] = &$plugin;
            }
        }
    }

    protected function cleanSaveData(&$data)
    {
        if (empty($data)) {
            return;
        }

        if (is_array($data)) {
            foreach ($data as &$arrayData) {
                $this->cleanSaveData($arrayData);
            }

            return;
        }

        if(is_string($data)) {
            $data = trim(str_replace(array("\n", "\t", "\r"), "", $data));
        }

        return;
    }

    /**
     * @param \Zend\Cache\Storage\StorageInterface $cache
     */
    public function setCache($cache)
    {
        $this->cache = $cache;
    }

    /**
     * @return \Zend\Cache\Storage\StorageInterface
     */
    public function getCache()
    {
        return $this->cache;
    }

    /**
     * @param \Rcm\Service\ContainerManager $containerManager
     */
    public function setContainerManager($containerManager)
    {
        $this->containerManager = $containerManager;
    }

    /**
     * @return \Rcm\Service\ContainerManager
     */
    public function getContainerManager()
    {
        return $this->containerManager;
    }

    /**
     * @param \Rcm\Service\DomainManager $domainManager
     */
    public function setDomainManager($domainManager)
    {
        $this->domainManager = $domainManager;
    }

    /**
     * @return \Rcm\Service\DomainManager
     */
    public function getDomainManager()
    {
        return $this->domainManager;
    }

    /**
     * @param \Rcm\Service\LayoutManager $layoutManager
     */
    public function setLayoutManager($layoutManager)
    {
        $this->layoutManager = $layoutManager;
    }

    /**
     * @return \Rcm\Service\LayoutManager
     */
    public function getLayoutManager()
    {
        return $this->layoutManager;
    }

    /**
     * @param \Rcm\Repository\Site $siteRepo
     */
    public function setSiteRepo($siteRepo)
    {
        $this->siteRepo = $siteRepo;
    }

    /**
     * @return \Rcm\Repository\Site
     */
    public function getSiteRepo()
    {
        return $this->siteRepo;
    }

    /**
     * @param mixed $domain
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
    }

    /**
     * @return mixed
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @param \Rcm\Service\PageManager $pageManager
     */
    public function setPageManager($pageManager)
    {
        $this->pageManager = $pageManager;
    }

    /**
     * @return \Rcm\Service\PageManager
     */
    public function getPageManager()
    {
        return $this->pageManager;
    }

    /**
     * @param \Rcm\Service\PluginManager $pluginManager
     */
    public function setPluginManager($pluginManager)
    {
        $this->pluginManager = $pluginManager;
    }

    /**
     * @return \Rcm\Service\PluginManager
     */
    public function getPluginManager()
    {
        return $this->pluginManager;
    }

}
