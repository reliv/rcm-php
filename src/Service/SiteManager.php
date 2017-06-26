<?php

namespace RcmAdmin\Service;

use Doctrine\ORM\EntityManager;
use Rcm\Entity\Country;
use Rcm\Entity\Domain;
use Rcm\Entity\Language;
use Rcm\Entity\Site;
use Rcm\Tracking\Exception\TrackingException;
use Rcm\Tracking\Model\Tracking;
use RcmUser\Service\RcmUserService;
use RcmUser\User\Entity\User;

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
     * @return \RcmUser\User\Entity\User
     * @throws TrackingException
     */
    protected function getCurrentUserTracking()
    {
        $user = $this->getCurrentUser();

        if (empty($user)) {
            throw new TrackingException('A valid user is required in ' . self::class);
        }

        return $user;
    }

    /**
     * @deprecated
     * getCurrentAuthor
     *
     * @param string $default
     *
     * @return string
     */
    public function getCurrentAuthor($default = 'Unknown Author')
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
    public function createSite(
        Site $newSite
    ) {
        $newSite = $this->prepareNewSite($newSite);

        $entityManager = $this->getEntityManager();

        /** @var \Rcm\Repository\Page $pageRepo */
        $pageRepo = $entityManager->getRepository(\Rcm\Entity\Page::class);

        $user = $this->getCurrentUserTracking();

        $pageRepo->createPages(
            $newSite,
            $this->getDefaultSitePageSettings($user),
            true,
            false
        );

        $entityManager->persist($newSite);

        $entityManager->flush();

        $this->createPagePlugins(
            $newSite,
            $user->getId(),
            'New site creation in ' . self::class,
            $this->getDefaultSitePageSettings($user),
            false
        );

        $entityManager->flush();

        return $newSite;
    }

    /**
     * copySite
     *
     * @param Site   $existingSite
     * @param Domain $domain
     * @param bool   $doFlush
     *
     * @return Site
     */
    public function copySite(
        Site $existingSite,
        Domain $domain,
        $doFlush = false
    ) {
        $entityManager = $this->getEntityManager();

        $user = $this->getCurrentUserTracking();

        $copySite = $existingSite->newInstance(
            $user->getId(),
            'Copy site in ' . self::class
        );
        $copySite->setSiteId(null);
        $copySite->setDomain($domain);

        $pages = $copySite->getPages();

        foreach ($pages as &$page) {
            $page->setAuthor($user->getName());
        }

        $entityManager->persist($copySite);

        if ($doFlush) {
            $entityManager->flush();
        }

        return $copySite;
    }

    /**
     * copySiteAndPopulate
     *
     * @param Site   $existingSite
     * @param Domain $domain
     * @param array  $data
     * @param bool   $doFlush
     *
     * @return Site
     */
    public function copySiteAndPopulate(
        Site $existingSite,
        Domain $domain,
        $data = [],
        $doFlush = false
    ) {
        $entityManager = $this->getEntityManager();

        $copySite = $this->copySite($existingSite, $domain, false);

        $copySite->populate($data);

        if ($doFlush) {
            $entityManager->flush($copySite);
        }

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
            throw new \Exception(
                "Site ID must be empty to create new site, id {$siteId} given."
            );
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
            throw new \Exception(
                'languageIso6392t default is required to create new site.'
            );
        }

        // Country
        if (empty($data['countryId'])) {
            throw new \Exception(
                'CountryId default is required to create new site.'
            );
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
     * @param User $createdByUser
     *
     * @return mixed
     */
    public function getDefaultSitePageSettings(User $createdByUser)
    {
        $myConfig = $this->getDefaultSiteSettings();

        $pagesData = $myConfig['pages'];

        // Set the author for each
        foreach ($pagesData as $key => $pageData) {
            $pagesData[$key]['createdByUserId'] = $createdByUser->getId();
            $pagesData[$key]['createdReason'] = 'Default page creation in ' . self::class;
            $pagesData[$key]['author'] = $createdByUser->getName();
        }

        return $pagesData;
    }

    /**
     * @param Site   $site
     * @param string $createdByUserId
     * @param string $createdReason
     * @param array  $pagesData
     * @param bool   $doFlush
     *
     * @return void
     * @throws \Exception
     */
    protected function createPagePlugins(
        Site $site,
        string $createdByUserId,
        string $createdReason = Tracking::UNKNOWN_REASON,
        $pagesData = [],
        $doFlush = true
    ) {
        $entityManager = $this->getEntityManager();

        /** @var \Rcm\Repository\Page $pageRepo */
        $pageRepo = $entityManager->getRepository(
            \Rcm\Entity\Page::class
        );

        /** @var \Rcm\Repository\PluginInstance $pluginInstanceRepo */
        $pluginInstanceRepo = $entityManager->getRepository(
            \Rcm\Entity\PluginInstance::class
        );

        /** @var \Rcm\Repository\PluginWrapper $pluginWrapperRepo */
        $pluginWrapperRepo = $entityManager->getRepository(
            \Rcm\Entity\PluginWrapper::class
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
                        $createdByUserId,
                        $createdReason,
                        null
                    );

                    $pluginData['pluginInstanceId']
                        = $pluginInstance->getInstanceId();

                    $wrapper = $pluginWrapperRepo->savePluginWrapper(
                        $pluginData,
                        $site,
                        $createdByUserId,
                        $createdReason,
                        null
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
