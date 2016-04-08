<?php

namespace RcmAdmin\Service;

use Doctrine\ORM\EntityManager;
use Rcm\Entity\Country;
use Rcm\Entity\Domain;
use Rcm\Entity\Language;
use Rcm\Entity\Site;
use RcmUser\Service\RcmUserService;

/**
 * Class ManageSites
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class SiteManager
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * SiteManager constructor.
     *
     * @param array          $config
     * @param EntityManager  $entityManager
     * @param RcmUserService $rcmUserService
     */
    public function __construct(
        $config,
        EntityManager $entityManager,
        RcmUserService $rcmUserService
    ) {
        $this->config = $config;
        $this->entityManager = $entityManager;
        $this->rcmUserService = $rcmUserService;
    }

    /**
     * getConfig
     *
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * getEntityManager
     *
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * getCurrentUser
     *
     * @return null|\RcmUser\User\Entity\User
     */
    public function getCurrentUser()
    {
        return $this->rcmUserService->getCurrentUser();
    }

    /**
     * getCurrentAuthor
     *
     * @param string $default
     *
     * @return string
     */
    protected function getCurrentAuthor($default = 'Unknown Author')
    {
        $user = $this->getCurrentUser();

        if (empty($user)) {
            return $default;
        }

        return $user->getName();
    }

    /**
     * createSite
     *
     * @param Site $newSite
     *
     * @return Site
     * @throws \Exception
     */
    public function createSite(Site $newSite)
    {
        $newSite = $this->prepareNewSite($newSite);

        $entityManager = $this->getEntityManager();

        /** @var \Rcm\Repository\Page $pageRepo */
        $pageRepo = $entityManager->getRepository('\Rcm\Entity\Page');

        $author = $this->getCurrentAuthor();

        $pageRepo->createPages(
            $newSite,
            $this->getDefaultSitePageSettings($author),
            true,
            false
        );

        $entityManager->persist($newSite);

        $entityManager->flush();

        $this->createPagePlugins(
            $newSite,
            $this->getDefaultSitePageSettings($author),
            false
        );

        $entityManager->flush();

        return $newSite;
    }

    /**
     * copySite
     *
     * @param Site   $site
     * @param Domain $domain
     *
     * @return Site
     * @throws \Exception
     */
    public function copySite(
        Site $existingSite,
        Site $newSite,
        Domain $domain
    ) {
        $entityManager = $this->getEntityManager();

        $copySite = clone($existingSite);
        $copySite->setSiteId(null);
        $newSite->setSiteId(null);
        $copySite = $this->prepareNewSite($newSite);
        $copySite->setDomain($domain);
        $copySite->populateFromObject($newSite);

        $author = $this->getCurrentAuthor();

        $pages = $copySite->getPages();

        foreach ($pages as &$page) {
            $page->setAuthor($author);
        }

        $entityManager->persist($copySite);
        $entityManager->flush();

        return $copySite;
    }

    /**
     * prepareNewSite
     *
     * @param Site $newSite
     *
     * @return Site
     * @throws \Exception
     */
    protected function prepareNewSite(Site $newSite)
    {
        $siteId = $newSite->getSiteId();
        if (!empty($siteId)) {
            throw new \Exception("Site ID must be empty to create new site, id {$siteId} given.");
        }

        if (empty($newSite->getDomain())) {
            throw new \Exception('Domain is required to create new site.');
        }

        return $this->prepareDefaultValues($newSite);
    }

    /**
     * prepareDefaultValues
     *
     * @param Site $site
     *
     * @return Site
     */
    protected function prepareDefaultValues(Site $site)
    {
        $defaults = $this->getDefaultSiteValues();

        foreach ($defaults as $key => $value) {
            $getMethod = 'get' . ucfirst($key);
            $setMethod = 'set' . ucfirst($key);

            $currentValue = $site->$getMethod();

            if (empty($currentValue)) {
                $site->$setMethod($value);
            }
        }

        return $site;
    }

    /**
     * getDefaultSiteSettings
     *
     * @return mixed
     * @throws \Exception
     */
    public function getDefaultSiteValues()
    {
        $data = $this->getDefaultSiteSettings();

        // Site Id
        if (empty($data['siteId'])) {
            $data['siteId'] = null;
        }

        // Language
        if (empty($data['languageIso6392t'])) {
            throw new \Exception('languageIso6392t default is required to create new site.');
        }

        // Country
        if (empty($data['countryId'])) {
            throw new \Exception('CountryId default is required to create new site.');
        }

        return $this->prepareSiteData($data);
    }

    /**
     * prepareSiteData
     *
     * @param array $data
     *
     * @return array
     * @throws \Exception
     */
    public function prepareSiteData(array $data)
    {
        if (!empty($data['languageIso6392t'])) {
            $data['language'] = $this->getLanguage($data['languageIso6392t']);
        }

        if (!empty($data['countryId'])) {
            $data['country'] = $this->getCountry($data['countryId']);
        }

        return $data;
    }

    /**
     * getCountry
     *
     * @param string $countryId
     *
     * @return null|object
     * @throws \Exception
     */
    public function getCountry($countryId)
    {
        $entityManager = $this->getEntityManager();

        /** @var \Rcm\Repository\Country $countryRepo */
        $countryRepo = $entityManager->getRepository('\Rcm\Entity\Country');

        $country = $countryRepo->find(
            $countryId
        );

        if (!$country instanceof Country) {
            throw new \Exception("Country {$countryId} could not be found.");
        }

        return $country;
    }

    /**
     * getLanguage
     *
     * @param string $languageIso6392t
     *
     * @return null|object
     * @throws \Exception
     */
    public function getLanguage($languageIso6392t)
    {
        $entityManager = $this->getEntityManager();

        /** @var \Rcm\Repository\Language $languageRepo */
        $languageRepo = $entityManager->getRepository(
            '\Rcm\Entity\Language'
        );

        $language = $languageRepo->getLanguageByString(
            $languageIso6392t,
            'iso639_2t'
        );

        if (!$language instanceof Language) {
            throw new \Exception("Language {$languageIso6392t} could not be found.");
        }

        return $language;
    }

    /**
     * getDomain
     *
     * @param $domainName
     *
     * @return null|object
     * @throws \Exception
     */
    public function getDomain($domainName)
    {
        $entityManager = $this->getEntityManager();

        /** @var \Rcm\Repository\Domain $domainRepo */
        $domainRepo = $entityManager->getRepository(
            '\Rcm\Entity\Domain'
        );

        $domain = $domainRepo->getDomainByName($domainName);

        if (!$domain instanceof Domain) {
            throw new \Exception("Domain {$domainName} could not be found.");
        }

        return $domain;
    }

    /**
     * getDefaultSiteSettings
     *
     * @return array
     */
    public function getDefaultSiteSettings()
    {
        $config = $this->getConfig();

        return $config['rcmAdmin']['defaultSiteSettings'];
    }

    /**
     * getDefaultSitePageSettings
     *
     * @return array
     */
    public function getDefaultSitePageSettings($author)
    {
        $myConfig = $this->getDefaultSiteSettings();

        $pagesData = $myConfig['pages'];

        // Set the author for each
        foreach ($pagesData as $key => $pageData) {
            $pagesData[$key]['author'] = $author;
        }

        return $pagesData;
    }

    /**
     * createPagePlugins
     *
     * @param Site  $site
     * @param array $pagesData
     * @param bool  $doFlush
     *
     * @return void
     * @throws \Exception
     */
    protected function createPagePlugins(
        Site $site,
        $pagesData = [],
        $doFlush = true
    ) {
        $entityManager = $this->getEntityManager();

        /** @var \Rcm\Repository\Page $pageRepo */
        $pageRepo = $entityManager->getRepository('\Rcm\Entity\Page');

        /** @var \Rcm\Repository\PluginInstance $pluginInstanceRepo */
        $pluginInstanceRepo = $entityManager->getRepository(
            '\Rcm\Entity\PluginInstance'
        );

        /** @var \Rcm\Repository\PluginWrapper $pluginWrapperRepo */
        $pluginWrapperRepo = $entityManager->getRepository(
            '\Rcm\Entity\PluginWrapper'
        );

        foreach ($pagesData as $pageName => $pageData) {
            if (empty($pageData['plugins'])) {
                continue;
            }

            $page = $pageRepo->getPageByName($site, $pageData['name']);

            if (!empty($page)) {
                $pageRevision = $page->getPublishedRevision();

                if (empty($pageRevision)) {
                    throw new \Exception(
                        "Could not find published revision for page {$page->getPageId()}"
                    );
                }

                foreach ($pageData['plugins'] as $pluginData) {
                    $pluginInstance = $pluginInstanceRepo->createPluginInstance(
                        $pluginData,
                        $site,
                        false
                    );

                    $pluginData['pluginInstanceId']
                        = $pluginInstance->getInstanceId();

                    $wrapper = $pluginWrapperRepo->savePluginWrapper(
                        $pluginData,
                        $site
                    );

                    $pageRevision->addPluginWrapper($wrapper);

                    $entityManager->persist($pageRevision);
                }
            }
        }

        if ($doFlush) {
            $entityManager->flush();
        }
    }
}
