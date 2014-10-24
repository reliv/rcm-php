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
use Rcm\Entity\ContainerAbstract;
use Rcm\Entity\ContainerInterface;
use Rcm\Entity\Country;
use Rcm\Entity\Language;
use Rcm\Entity\PluginWrapper;
use Rcm\Entity\Revision;
use Rcm\Entity\Site;
use Rcm\Exception\InvalidArgumentException;
use Rcm\Exception\RuntimeException;
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
     * Alias of Get Not Authorized Page
     *
     * @return string Path to login page
     */
    public function getCurrentSiteNotAuthorizedPage()
    {
        return $this->getSiteNotAuthorizedPage();
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

        $siteInfo = $this->siteRepo->getSiteInfo($siteId);

        if (empty($siteInfo)) {
            return array();
        }

        $this->siteInfo = $siteInfo;

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
     * Get the sites Not Authorized Page
     *
     * @return string
     */
    public function getSiteNotAuthorizedPage()
    {
        $siteInfo = $this->getSiteInfo();
        return $siteInfo['notAuthorizedPage'];
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

            if (!empty($siteInfo)) {
                $country = new Country();
                $country->setCountryName($siteInfo['country']['countryName']);
                $country->setIso2($siteInfo['country']['iso2']);
                $country->setIso3($siteInfo['country']['iso3']);

                $this->currentSiteCountry = $country;
            }
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
        return $this->getSiteLocale($siteInfo);
    }

    /**
     * getSiteLocale - Returns php compatible locale string
     *
     * @param $siteInfo
     *
     * @return string
     */
    public function getSiteLocale($siteInfo)
    {
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
        return $this->siteRepo->getSites(true);
    }

    /**
     * Get an array of active site objects
     *
     * @return array
     */
    public function getAllSites()
    {
        return $this->siteRepo->getSites(false);
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
            return $this->siteRepo->findOneBy(
                array('siteId' => $siteId)
            );
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
        $saveData,
        $author
    ) {
        /** @var \Rcm\Entity\Site $siteEntity */
        $siteEntity = $this->siteRepo->findOneBy(array('siteId' => $this->currentSiteId));

        if (empty($siteEntity)) {
            throw new SiteNotFoundException('Unable to locate site.');
        }

        $this->prepSaveData($saveData);

        foreach($saveData['containers'] as $containerName => $containerData) {
            /** @var \Rcm\Entity\Container $container */
            $container = $siteEntity->getContainer($containerName);

            $this->saveContainer($container, $containerData, $author);
        }

        $page = $siteEntity->getPage($pageName, $pageType);
        return $this->saveContainer($page, $saveData['pageContainer'], $author, $pageRevision);
    }

    protected function saveContainer(
        ContainerInterface $container,
        $containerData,
        $author,
        $revisionNumber=null
    ) {

        $revision = null;
        $publishRevision = false;

        if (empty($container)) {
            throw new RuntimeException('Invalid container');
        }

        if (empty($revisionNumber)) {
            $revision = $container->getPublishedRevision();
            $publishRevision = true;
        } else {
            $revision = $container->getRevisionById($revisionNumber);
        }

        if (empty($revision)) {
            throw new RuntimeException('Unable to locate revision.');
        }

        $md5 = md5(serialize($containerData));

        if (empty($revision) || $revision->getMd5() == $md5) {
            return null;
        }

        $newRevision = new Revision();
        $newRevision->setAuthor($author);
        $newRevision->setMd5($md5);

        $isDirty = false;

        if (empty($containerData)) {
            $isDirty = true;
        } else {
            foreach ($containerData as $pluginData) {
                /** @var \Rcm\Entity\PluginWrapper $pluginWrapper */
                $pluginWrapper = $revision->getPluginWrapper(
                    $pluginData['instanceId']
                );
                $newPluginWrapper = $this->savePluginWrapper(
                    $pluginData,
                    $pluginWrapper
                );
                $newRevision->addPluginWrapper($newPluginWrapper);

                if (!empty($pluginWrapper)
                    && $pluginWrapper->getPluginWrapperId()
                    == $newPluginWrapper->getPluginWrapperId()
                    && ($pluginWrapper->getInstance()->getInstanceId()
                        == $newPluginWrapper->getInstance()->getInstanceId()
                        || $pluginWrapper->getInstance()->isSiteWide())
                ) {
                    continue;
                }

                $isDirty = true;
            }
        }

        if ($isDirty) {
            $this->siteRepo->getDoctrine()->persist($newRevision);
            $container->addRevision($newRevision);

            if ($publishRevision) {
                $newRevision->publishRevision();
                $container->setPublishedRevision($newRevision);
            }

            $stagedRevision = $container->getStagedRevision();

            if (!empty($stagedRevision) && $revision->getRevisionId() == $stagedRevision->getRevisionId()) {
                $container->setStagedRevision($newRevision);
            }

            $this->siteRepo->getDoctrine()->flush();
            return $newRevision->getRevisionId();
        }

        return null;
    }

    /**
     * @param                    $pluginData
     * @param null|PluginWrapper $oldWrapper
     *
     * @returns PluginWrapper
     *
     * @throws \Rcm\Exception\RuntimeException
     */

    protected function savePluginWrapper($pluginData, $oldWrapper=null)
    {
        if (!empty($oldWrapper) && !is_a($oldWrapper, '\Rcm\Entity\PluginWrapper')) {
            throw new RuntimeException('Wrapper passed in is not a valid plugin wrapper.');
        }

        $pluginInstance = $this->pluginManager->savePlugin(
            $pluginData['instanceId'],
            $pluginData['name'],
            $pluginData['saveData'],
            $pluginData['isSitewide'],
            $pluginData['sitewideName']
        );

        if (!empty($oldWrapper)
            && $oldWrapper->getRenderOrderNumber() == $pluginData['rank']
            && $oldWrapper->getDivFloat() == $pluginData['float']
            && $oldWrapper->getHeight() == $pluginData['height']
            && $oldWrapper->getWidth() == $pluginData['width']
            && $oldWrapper->getLayoutContainer() == $pluginData['containerName']
            && ($oldWrapper->getInstance()->getInstanceId() == $pluginInstance->getInstanceId()
                || $pluginInstance->isSiteWide())
        ) {
            return $oldWrapper;
        }

        $pluginWrapper = new PluginWrapper();
        $pluginWrapper->setDivFloat($pluginData['float']);
        $pluginWrapper->setHeight($pluginData['height']);
        $pluginWrapper->setWidth($pluginData['width']);
        $pluginWrapper->setLayoutContainer($pluginData['containerName']);
        $pluginWrapper->setInstance($pluginInstance);
        $pluginWrapper->setRenderOrderNumber($pluginData['rank']);

        $this->siteRepo->getDoctrine()->persist($pluginWrapper);
        return $pluginWrapper;
    }

    /**
     * Prep and validate data array to save
     *
     * @param $data
     *
     * @throws InvalidArgumentException
     */
    protected function prepSaveData(&$data)
    {
        ksort($data);
        $data['containers'] = array();
        $data['pageContainer'] = array();

        if (empty($data['plugins'])) {
            throw new InvalidArgumentException('Save Data missing plugins.
                Please make sure the data you\'re attempting to save is correctly formatted.
            ');
        }

        foreach ($data['plugins'] as &$plugin)
        {
            $this->cleanSaveData($plugin['saveData']);

            /*
             * Set some default data to keep notices from being thrown.
             */
            if (empty($plugin['height'])) {
                $plugin['height'] = 0;
            }

            if (empty($plugin['width'])) {
                $plugin['width'] = 0;
            }

            if (empty($plugin['float'])) {
                $plugin['float'] = 'left';
            }

            if (empty($plugin['float'])) {
                $plugin['float'] = 'left';
            }

            /* Patch for a Json Bug */
            if (!empty($plugin['isSitewide'])
                && $plugin['isSitewide'] != 'false'
                && $plugin['isSitewide'] != '0'
            ) {
                $plugin['isSitewide'] = 1;
            } else {
                $plugin['isSitewide'] = 0;
            }


            if (empty($plugin['sitewideName'])) {
                $plugin['sitewideName'] = null;
            }

            $plugin['rank'] = (int) $plugin['rank'];
            $plugin['height'] = (int) $plugin['height'];
            $plugin['width'] = (int) $plugin['width'];

            $plugin['containerName'] = $plugin['containerId'];

            if ($plugin['containerType'] == 'layout') {
                $data['containers'][$plugin['containerId']][] = &$plugin;
            } else {
                $data['pageContainer'][] = &$plugin;
            }

            ksort($plugin['saveData']);
        }
    }

    /**
     * Save data clean up.
     *
     * @param $data
     */
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
