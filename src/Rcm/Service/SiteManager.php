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

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Rcm\Entity\Country;
use Rcm\Entity\Language;
use Rcm\Exception\SiteNotFoundException;
use Rcm\Repository\Site;
use Doctrine\ORM\EntityRepository;
use Zend\Cache\Storage\StorageInterface;
use Zend\Http\PhpEnvironment\Request as PhpEnvironmentRequest;
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
    /** @var \Rcm\Service\DomainManager  */
    protected $domainManager;

    /** @var \Rcm\Repository\Site  */
    protected $siteRepo;

    /** @var \Zend\Cache\Storage\StorageInterface  */
    protected $cache;

    /** @var \Zend\Http\PhpEnvironment\Request  */
    protected $request;

    /** @var integer */
    protected $currentSiteId;

    /** @var array */
    protected $currentSiteInfo;

    /** @var Country|array */
    protected $currentSiteCountry;

    /** @var Language|array */
    protected $currentSiteLanguage;

    /**
     * Constructor
     *
     * @param DomainManager    $domainManager Rcm Domain Manager
     * @param EntityRepository $siteRepo      Doctrine Entity Manager
     * @param StorageInterface $cache         Zend Cache Manager
     * @param RequestInterface $request       Zend Request Object
     */
    public function __construct(
        DomainManager    $domainManager,
        EntityRepository $siteRepo,
        StorageInterface $cache,
        RequestInterface $request
    ) {
        $this->domainManager = $domainManager;
        $this->siteRepo      = $siteRepo;
        $this->cache         = $cache;
        $this->request       = $request;
    }

    /**
     * Get the current sites information
     *
     * @return array
     */
    public function getCurrentSiteInfo()
    {
        if (!empty($this->currentSiteInfo)) {
            return $this->currentSiteInfo;
        }

        $currentSiteId = $this->getCurrentSiteId();
        $cacheKey = 'rcm_site_info_'.$currentSiteId;

        if ($this->cache->hasItem($cacheKey)) {
            $this->currentSiteInfo = $this->cache->getItem($cacheKey);
            return $this->currentSiteInfo;
        }

        $this->currentSiteInfo = $this->siteRepo->getSiteInfo($currentSiteId);

        $this->cache->setItem($cacheKey, $this->currentSiteInfo);

        return $this->currentSiteInfo;
    }

    /**
     * Get Current Sites Login Page
     *
     * @return string Path to login page
     */
    public function getCurrentSiteLoginPage()
    {
        $siteInfo = $this->getCurrentSiteInfo();

        return $siteInfo['loginPage'];
    }

    /**
     * Get the current sites theme
     *
     * @return string Theme
     */
    public function getCurrentSiteTheme()
    {
        $siteInfo = $this->getCurrentSiteInfo();

        return $siteInfo['theme'];
    }

    /**
     * Get the current Sites default Zend Framework Layout template.
     *
     * @return string
     */
    public function getCurrentSiteDefaultLayout()
    {
        $siteInfo = $this->getCurrentSiteInfo();

        return $siteInfo['siteLayout'];
    }

    /**
     * Get the current sites unique id
     *
     * @return integer siteId
     */
    public function getCurrentSiteId()
    {
        if (empty($this->currentSiteId)) {
            $this->currentSiteId = $this->getCurrentSiteIdFromDomain();
        }

        return $this->currentSiteId;
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
     * Get Current Site Id From Domain
     *
     * @return integer|null SiteId
     * @throws \Rcm\Exception\SiteNotFoundException
     */
    protected function getCurrentSiteIdFromDomain()
    {

        if (!$this->request instanceof PhpEnvironmentRequest) {
            return null;
        }

        $domainList = $this->domainManager->getDomainList();

        $serverParams = $this->request->getServer();

        $currentDomain = $serverParams->get('HTTP_HOST');

        if (empty($domainList[$currentDomain])
            || empty($domainList[$currentDomain]['siteId'])
        ) {
            throw new SiteNotFoundException(
                'No site found for request domain: '.$currentDomain
            );
        }

        return $domainList[$currentDomain]['siteId'];
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
}