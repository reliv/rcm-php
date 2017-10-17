<?php

namespace Rcm\Api\Repository\Site;

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
 * @author James Jervis - https://github.com/jerv13
 */
class CreateSite
{
    const OPTION_AUTHOR = 'author';
    const OPTION_CREATE_DEFAULT_PAGES = 'createDefaultPages';
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
     * @param EntityManager $entityManager
     */
    public function __construct(
        EntityManager $entityManager
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
    }

    /**
     * @param array  $siteData
     * @param string $createdByUserId
     * @param string $createdReason
     * @param array  $defaultSiteSettings
     * @param array  $options
     *
     * @return Site
     * @throws \Exception
     */
    public function __invoke(
        array $siteData,
        string $createdByUserId,
        string $createdReason = Tracking::UNKNOWN_REASON,
        array $defaultSiteSettings = [],
        array $options = []
    ) {
        $author = (array_key_exists(self::OPTION_AUTHOR, $options) ? $options[self::OPTION_AUTHOR] : $createdByUserId);
        $siteData = $this->prepareSiteData($siteData);

        if (empty($siteData['domain']) && empty($siteData['domainName'])) {
            throw new \Exception(
                'siteData[domain] (existing domain) or siteData[domainName] (new domain) must be set to create new site'
            );
        }

        if (!empty($siteData['domainName'])) {
            $siteData['domain'] = $this->domainRepository->createDomain(
                $siteData['domainName'],
                $createdByUserId,
                'Create new domain in ' . get_class($this)
                . ' for: ' . $createdReason
            );
        }

        /** @var \Rcm\Entity\Site $newSite */
        $newSite = new Site(
            $createdByUserId,
            'Create new site in ' . get_class($this)
            . ' for: ' . $createdReason
        );

        $newSite->populate($siteData);
        // make sure we don't have a siteId
        $newSite->setSiteId(null);

        $newSite = $this->prepareNewSite($newSite, $defaultSiteSettings);

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
     * @param Site  $newSite
     * @param array $defaultSiteSettings
     *
     * @return Site
     * @throws \Exception
     */
    protected function prepareNewSite(
        Site $newSite,
        array $defaultSiteSettings
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
            $defaultSiteSettings
        );
    }

    /**
     * @param array $siteData
     *
     * @return array
     * @throws \Exception
     */
    public function prepareSiteData(array $siteData)
    {
        if (!empty($siteData['languageIso6392t'])) {
            $siteData['language'] = $this->languageRepository->findOneBy(
                ['iso639_2t' => $siteData['languageIso6392t']]
            );

            if (empty($siteData['language'])) {
                throw new LanguageNotFoundException("Language {$siteData['languageIso6392t']} could not be found.");
            }
        }

        if (!empty($siteData['countryId'])) {
            $siteData['country'] = $this->countryRepository->findOneBy(
                ['iso3' => $siteData['countryId']]
            );

            if (empty($siteData['country'])) {
                throw new CountryNotFoundException("Country {$siteData['countryId']} could not be found.");
            }
        }

        if (!empty($siteData['countryIso3'])) {
            $siteData['country'] = $this->countryRepository->findOneBy(
                ['iso3' => $siteData['countryIso3']]
            );
            if (empty($siteData['country'])) {
                throw new CountryNotFoundException("Country {$siteData['countryIso3']} could not be found.");
            }
        }

        return $siteData;
    }

    /**
     * @param Site  $site
     * @param array $defaultSiteSettings
     *
     * @return Site
     */
    protected function prepareDefaultValues(
        Site $site,
        array $defaultSiteSettings
    ) {
        $defaults = $this->getDefaultSiteValues($defaultSiteSettings);

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
     * @param array $defaultSiteSettings
     *
     * @return array
     * @throws \Exception
     */
    public function getDefaultSiteValues(
        array $defaultSiteSettings
    ) {
        // Site Id
        if (empty($defaultSiteSettings['siteId'])) {
            $defaultSiteSettings['siteId'] = null;
        }

        // Language
        if (empty($defaultSiteSettings['languageIso6392t'])) {
            throw new \Exception(
                'languageIso6392t default is required to create new site.'
            );
        }

        // Country
        if (empty($defaultSiteSettings['countryId'])) {
            throw new \Exception(
                'CountryId default is required to create new site.'
            );
        }

        return $this->prepareSiteData($defaultSiteSettings);
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
