<?php

namespace Rcm\Api\Repository\Site;

use Aws\Swf\Exception\DomainAlreadyExistsException;
use Doctrine\ORM\EntityManager;
use Rcm\Api\Repository\Options;
use Rcm\Entity\Country;
use Rcm\Entity\Domain;
use Rcm\Entity\Language;
use Rcm\Entity\Page;
use Rcm\Entity\PluginInstance;
use Rcm\Entity\PluginWrapper;
use Rcm\Entity\Site;
use Rcm\Exception\CountryNotFoundException;
use Rcm\Exception\LanguageNotFoundException;
use Rcm\Tracking\Model\Tracking;

/**
 * @deprecated Use CreateSite - Client should handle defaults
 * @author James Jervis - https://github.com/jerv13
 */
class CreateSiteOld
{
    const OPTION_AUTHOR = 'author';
    const OPTION_CREATE_DEFAULT_PAGES = 'createDefaultPages';
    const OPTION_DEFAULT_SITE_SETTINGS = 'defaultSiteSettings';

    const DEFAULT_LANGUAGE_ISO6392T = 'eng';
    const DEFAULT_COUNTRY_ISO3 = 'USA';

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var \Rcm\Repository\Site
     */
    protected $repository;

    /**
     * @var \Rcm\Repository\Domain
     */
    protected $domainRepository;

    /**
     * @var \Rcm\Repository\Page
     */
    protected $pageRepository;

    /**
     * @var \Rcm\Repository\PluginInstance
     */
    protected $pluginInstanceRepository;

    /**
     * @var \Rcm\Repository\PluginWrapper
     */
    protected $pluginWrapperRepository;

    /**
     * @var \Rcm\Repository\PluginWrapper
     */
    protected $countryRepository;

    /**
     * @var \Rcm\Repository\PluginWrapper
     */
    protected $languageRepository;

    /**
     * @var string
     */
    protected $defaultLanguageIso6392t;

    /**
     * @var string
     */
    protected $defaultCountryIso3;

    /**
     * @param EntityManager $entityManager
     * @param string        $defaultLanguageIso6392t
     * @param string        $defaultCountryIso3
     */
    public function __construct(
        EntityManager $entityManager,
        string $defaultLanguageIso6392t = self::DEFAULT_LANGUAGE_ISO6392T,
        string $defaultCountryIso3 = self::DEFAULT_COUNTRY_ISO3
    ) {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(
            Site::class
        );

        $this->domainRepository = $entityManager->getRepository(
            Domain::class
        );

        $this->pageRepository = $entityManager->getRepository(
            Page::class
        );

        $this->pluginInstanceRepository = $entityManager->getRepository(
            PluginInstance::class
        );

        $this->pluginWrapperRepository = $entityManager->getRepository(
            PluginWrapper::class
        );

        $this->countryRepository = $entityManager->getRepository(
            Country::class
        );

        $this->languageRepository = $entityManager->getRepository(
            Language::class
        );

        $this->defaultLanguageIso6392t = $defaultLanguageIso6392t;
        $this->defaultCountryIso3 = $defaultCountryIso3;
    }

    /**
     * @param array  $siteData
     * @param string $createdByUserId
     * @param string $createdReason
     * @param array  $options
     *
     * @return Site
     * @throws \Exception
     */
    public function __invoke(
        array $siteData,
        string $createdByUserId,
        string $createdReason = Tracking::UNKNOWN_REASON,
        array $options = []
    ) {
        $author = (array_key_exists(self::OPTION_AUTHOR, $options) ? $options[self::OPTION_AUTHOR] : $createdByUserId);
        $siteData = $this->prepareSiteData(
            $siteData,
            $createdByUserId,
            $createdReason
        );

        /** @var \Rcm\Entity\Site $newSite */
        $newSite = new Site(
            $createdByUserId,
            'Create new site in ' . get_class($this)
            . ' for: ' . $createdReason
        );

        $newSite->populate($siteData);
        // make sure we don't have a siteId
        $newSite->setSiteId(null);

        $defaultSiteSettings = Options::get(
            $options,
            self::OPTION_DEFAULT_SITE_SETTINGS,
            []
        );

        $newSite = $this->prepareNewSite(
            $newSite,
            $defaultSiteSettings,
            $createdByUserId,
            $createdReason
        );

        $createDefaultPages = Options::get(
            $options,
            self::OPTION_CREATE_DEFAULT_PAGES,
            true
        );

        if ($createDefaultPages) {
            $this->pageRepository->createPages(
                $newSite,
                $this->getDefaultSitePageSettings(
                    $defaultSiteSettings,
                    $author,
                    $createdByUserId,
                    $createdReason
                ),
                true,
                false
            );
        }

        $this->entityManager->persist($newSite);

        $this->entityManager->flush($newSite);

        if ($createDefaultPages) {
            $this->createPagePlugins(
                $newSite,
                $createdByUserId,
                'New site creation in ' . get_class($this) . ' for: ' . $createdReason,
                $this->getDefaultSitePageSettings(
                    $defaultSiteSettings,
                    $author,
                    $createdByUserId,
                    $createdReason
                ),
                true
            );
        }

        return $newSite;
    }

    /**
     * @param Site   $newSite
     * @param array  $defaultSiteSettings
     * @param string $createdByUserId
     * @param string $createdReason
     *
     * @return Site
     * @throws \Exception
     */
    protected function prepareNewSite(
        Site $newSite,
        array $defaultSiteSettings,
        string $createdByUserId,
        string $createdReason
    ) {
        $siteId = $newSite->getSiteId();
        if (!empty($siteId)) {
            throw new \Exception(
                "Site ID must be empty to create new site, id {$siteId} given."
            );
        }

        if (empty($newSite->getDomain())) {
            throw new \Exception('Domain is required to create new site.');
        }

        return $this->prepareDefaultValues(
            $newSite,
            $defaultSiteSettings,
            $createdByUserId,
            $createdReason
        );
    }

    /**
     * @param array  $siteData
     * @param string $createdByUserId
     * @param string $createdReason
     *
     * @return array
     */
    protected function prepareSiteData(
        array $siteData,
        string $createdByUserId,
        string $createdReason
    ) {
        $language = $this->buildLanguage($siteData);
        if (!empty($language)) {
            $siteData['language'] = $language;
        }

        $country = $this->buildCountry($siteData);
        if (!empty($country)) {
            $siteData['country'] = $country;
        }

        $domain = $this->buildDomain(
            $siteData,
            $createdByUserId,
            $createdReason
        );
        if (!empty($domain)) {
            $siteData['domain'] = $domain;
        }

        return $siteData;
    }

    /**
     * @param array $siteData
     *
     * @return Language|null
     */
    protected function buildLanguage(array $siteData)
    {
        if (!empty($siteData['language']) && $siteData['language'] instanceof Language) {
            return $siteData['language'];
        }

        if (!empty($siteData['languageIso6392t'])) {
            $language = $this->languageRepository->findOneBy(
                ['iso639_2t' => $siteData['languageIso6392t']]
            );

            if (empty($language)) {
                throw new LanguageNotFoundException("Language {$siteData['languageIso6392t']} could not be found.");
            }

            return $language;
        }

        return null;
    }

    /**
     * @param array $siteData
     *
     * @return Country|null
     */
    protected function buildCountry(array $siteData)
    {
        if (!empty($siteData['country']) && $siteData['country'] instanceof Country) {
            return $siteData['country'];
        }

        if (!empty($siteData['countryId'])) {
            $country = $this->countryRepository->findOneBy(
                ['iso3' => $siteData['countryId']]
            );

            if (empty($country)) {
                throw new CountryNotFoundException("Country {$siteData['countryId']} could not be found.");
            }

            return $country;
        }

        if (!empty($siteData['countryIso3'])) {
            $country = $this->countryRepository->findOneBy(
                ['iso3' => $siteData['countryIso3']]
            );
            if (empty($country)) {
                throw new CountryNotFoundException("Country {$siteData['countryIso3']} could not be found.");
            }

            return $country;
        }

        return null;
    }

    /**
     * @param array  $siteData
     * @param string $createdByUserId
     * @param string $createdReason
     *
     * @return Domain|null
     * @throws \Exception
     */
    protected function buildDomain(
        array $siteData,
        string $createdByUserId,
        string $createdReason
    ) {
        if (!empty($siteData['domain']) && $siteData['domain'] instanceof Domain) {
            return $siteData['domain'];
        }

        if (!empty($siteData['domainName'])) {
            $domain = $this->domainRepository->findOneBy(
                ['domain' => $siteData['domainName']]
            );

            if (!empty($domain)) {
                throw new DomainAlreadyExistsException(
                    "Domain {$siteData['domainName']} was found and should not be duplicated."
                );
            }

            return $this->domainRepository->createDomain(
                $siteData['domainName'],
                $createdByUserId,
                'Create new domain in ' . get_class($this)
                . ' for: ' . $createdReason
            );
        }

        return null;
    }

    /**
     * @param Site   $site
     * @param array  $defaultSiteSettings
     * @param string $createdByUserId
     * @param string $createdReason
     *
     * @return Site
     */
    protected function prepareDefaultValues(
        Site $site,
        array $defaultSiteSettings,
        string $createdByUserId,
        string $createdReason
    ) {
        $defaults = $this->getDefaultSiteValues(
            $defaultSiteSettings,
            $createdByUserId,
            $createdReason
        );

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
     * @param array  $defaultSiteSettings
     * @param string $createdByUserId
     * @param string $createdReason
     *
     * @return array
     */
    public function getDefaultSiteValues(
        array $defaultSiteSettings,
        string $createdByUserId,
        string $createdReason
    ) {
        // Site Id
        if (empty($defaultSiteSettings['siteId'])) {
            $defaultSiteSettings['siteId'] = null;
        }

        // Language
        if (empty($defaultSiteSettings['languageIso6392t'])) {
            $defaultSiteSettings['languageIso6392t'] = $this->defaultLanguageIso6392t;
        }

        // Country
        if (empty($defaultSiteSettings['countryId'])) {
            $defaultSiteSettings['countryId'] = $this->defaultCountryIso3;
        }

        return $this->prepareSiteData(
            $defaultSiteSettings,
            $createdByUserId,
            $createdReason
        );
    }

    /**
     * @param array  $defaultSiteSettings
     * @param string $author
     * @param string $createdByUserId
     * @param string $createdReason
     *
     * @return array|mixed
     */
    public function getDefaultSitePageSettings(
        array $defaultSiteSettings,
        string $author,
        string $createdByUserId,
        string $createdReason
    ) {
        $pagesData = (array_key_exists('pages', $defaultSiteSettings) ? $defaultSiteSettings['pages'] : []);

        // Set the author for each
        foreach ($pagesData as $key => $pageData) {
            $pagesData[$key]['createdByUserId'] = $createdByUserId;
            $pagesData[$key]['createdReason']
                = 'Default page creation in ' . get_class($this) . ' for: ' . $createdReason;
            $pagesData[$key]['author'] = $author;
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
        $pages = [];
        $pageRevisions = [];

        foreach ($pagesData as $pageName => $pageData) {
            if (empty($pageData['plugins'])) {
                continue;
            }

            $page = $this->pageRepository->getPageByName($site, $pageData['name']);

            if (empty($page)) {
                continue;
            }

            $page->setModifiedByUserId(
                $createdByUserId,
                $createdReason
            );

            $pages[] = $page;

            $pageRevision = $page->getPublishedRevision();

            if (empty($pageRevision)) {
                throw new \Exception(
                    "Could not find published revision for page {$page->getPageId()}"
                );
            }

            foreach ($pageData['plugins'] as $pluginData) {
                $pluginInstance = $this->pluginInstanceRepository->createPluginInstance(
                    $pluginData,
                    $site,
                    $createdByUserId,
                    $createdReason,
                    null,
                    $doFlush
                );

                $pluginData['pluginInstanceId'] = $pluginInstance->getInstanceId();

                $wrapper = $this->pluginWrapperRepository->savePluginWrapper(
                    $pluginData,
                    $site,
                    $createdByUserId,
                    $createdReason,
                    null,
                    $doFlush
                );

                $pageRevision->addPluginWrapper($wrapper);

                $pageRevision->setModifiedByUserId(
                    $createdByUserId,
                    $createdReason
                );

                $this->entityManager->persist($pageRevision);

                $pageRevisions[] = $pageRevision;
            }
        }

        if ($doFlush) {
            $this->entityManager->flush($pages);
            $this->entityManager->flush($pageRevisions);
        }
    }
}
