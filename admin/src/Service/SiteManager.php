<?php

namespace RcmAdmin\Service;

use Doctrine\ORM\EntityManager;
use Rcm\Entity\Container;
use Rcm\Entity\Country;
use Rcm\Entity\Domain;
use Rcm\Entity\Language;
use Rcm\Entity\Page;
use Rcm\Entity\Site;
use Rcm\ImmutableHistory\Site\SiteContent;
use Rcm\ImmutableHistory\Site\SiteLocator;
use Rcm\ImmutableHistory\VersionRepositoryInterface;
use Rcm\Page\PageTypes\PageTypes;
use Rcm\Tracking\Exception\TrackingException;
use Rcm\Tracking\Model\Tracking;
use RcmUser\Service\RcmUserService;
use RcmUser\User\Entity\UserInterface;

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

    protected $pageMutationService;

    protected $siteVersionRepo;

    /**
     * SiteManager constructor.
     *
     * @param array $config
     * @param EntityManager $entityManager
     * @param RcmUserService $rcmUserService
     */
    public function __construct(
        $config,
        EntityManager $entityManager,
        RcmUserService $rcmUserService,
        PageMutationService $pageMutationService,
        VersionRepositoryInterface $siteVersionRepo
    ) {
        $this->config = $config;
        $this->entityManager = $entityManager;
        $this->rcmUserService = $rcmUserService;
        $this->pageMutationService = $pageMutationService;
        $this->siteVersionRepo = $siteVersionRepo;
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
     * @deprecated Use Rcm\Repository\Site\CreateSite
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

        $entityManager->persist($newSite);
        $entityManager->flush($newSite);

        $this->siteVersionRepo->publishFromNothing(
            new SiteLocator($newSite->getDomainName()),
            $this->siteToImmutableSiteContent($newSite),
            $this->getCurrentUserTracking()->getId(),
            __CLASS__ . '::' . __FUNCTION__,
            $newSite->getSiteId()
        );

        foreach ($this->getDefaultSitePageSettings($user) as $name => $pageData) {
            $createdPage = $this->pageMutationService->createNewPage(
                $user,
                $newSite->getSiteId(),
                $pageData['name'],
                $pageData['pageType'],
                //Axosoft ticket #22776 was created to fix this @TODO. it is a pretty low priority issue
                $pageData //@TODO this is probably not in the correct format for plugins/blocks/pluginWrapper data
            );
            $this->pageMutationService->publishPageRevision(
                $user,
                $newSite->getSiteId(),
                $pageData['name'],
                $pageData['pageType'],
                $createdPage->getStagedRevision()->getRevisionId(),
                function ($pageName, $pageType = PageTypes::NORMAL, $pageRevision = null) {
                    if ($pageType !== PageTypes::NORMAL || $pageRevision !== null) {
                        throw new \Exception('Unsupported Case');

                        return '/' . $pageName;
                    }
                }
            );
        }

        return $newSite;
    }


    /**
     * @deprecated Use Rcm\Repository\Site\CopySite
     * copySiteAndPopulate
     *
     * @param Site $existingSite
     * @param Domain $domain
     * @param array $data
     * @param bool $doFlush
     *
     * @return Site
     */
    public function copySiteAndPopulate(
        Site $existingSite,
        Domain $domain,
        $data = []
    ) {
        $entityManager = $this->getEntityManager();

        $copySite = $this->copySite($existingSite, $domain);

        $copySite->populate($data);

        $this->siteVersionRepo->publishFromNothing(
            new SiteLocator($copySite->getDomainName()),
            $this->siteToImmutableSiteContent($copySite),
            $this->getCurrentUserTracking()->getId(),
            __CLASS__ . '::' . __FUNCTION__,
            $copySite->getSiteId()
        );

        return $copySite;
    }

    public function siteToImmutableSiteContent(Site $site): SiteContent
    {
        return new SiteContent(
            $site->getStatus(),
            $site->getCountryIso3(),
            $site->getLanguageId(),
            $site->getTheme(),
            $site->getSiteTitle(),
            $site->getFavIcon()
        );
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
     * getConfig
     *
     * @return array
     */
    protected function getConfig()
    {
        return $this->config;
    }

    /**
     * getEntityManager
     *
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * getCurrentUser
     *
     * @return null|\RcmUser\User\Entity\UserInterface
     */
    protected function getCurrentUser()
    {
        return $this->rcmUserService->getCurrentUser();
    }

    /**
     * @return \RcmUser\User\Entity\UserInterface
     * @throws TrackingException
     */
    protected function getCurrentUserTracking()
    {
        $user = $this->getCurrentUser();

        if (empty($user)) {
            throw new TrackingException('A valid user is required in ' . get_class($this));
        }

        return $user;
    }

    /**
     * @deprecated Use Rcm\Repository\Site\CopySite
     * copySite
     *
     * @param Site $existingSite
     * @param Domain $domain
     *
     * @return Site
     */
    protected function copySite(
        Site $existingSite,
        Domain $domain
    ) {
        $entityManager = $this->getEntityManager();

        $user = $this->getCurrentUserTracking();

        $domain->setModifiedByUserId(
            $user->getId(),
            'Copy site in ' . get_class($this)
        );

        $copySite = $existingSite->newInstance(
            $user->getId(),
            'Copy site in ' . get_class($this),
            false
        );
        $entityManager->persist($copySite);

        $copySite->setSiteId(null);
        $copySite->setDomain($domain);

        // NOTE: site::newInstance() does page copy too


        $containers = $copySite->getContainers();
        $containerRevisions = [];

        /** @var Container $container */
        foreach ($containers as $container) {
            $containerRevision = $container->getPublishedRevision();
            $containerRevisions[] = $containerRevision;
            $entityManager->persist($container);
            $entityManager->persist($containerRevision);
        }
        $entityManager->flush($containers->toArray());
        $entityManager->flush($containerRevisions);
        $entityManager->flush($domain);
        $entityManager->flush($copySite);

        /** @var Page $page */
        foreach ($existingSite->getPages() as $page) {
            if (empty($page->getPublishedRevision())) {
                continue; //Don't copy unpublished pages
            }
            $destinationPage = $this->pageMutationService->duplicatePage(
                $user,
                $page,
                $copySite->getSiteId(),
                $page->getName()
            );
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
     * getCountry
     *
     * @param string $countryId
     *
     * @return null|object
     * @throws \Exception
     */
    protected function getCountry($countryId)
    {
        $entityManager = $this->getEntityManager();

        /** @var \Rcm\Repository\Country $countryRepo */
        $countryRepo = $entityManager->getRepository(\Rcm\Entity\Country::class);

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
    protected function getLanguage($languageIso6392t)
    {
        $entityManager = $this->getEntityManager();

        /** @var \Rcm\Repository\Language $languageRepo */
        $languageRepo = $entityManager->getRepository(
            \Rcm\Entity\Language::class
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
    protected function getDomain($domainName)
    {
        $entityManager = $this->getEntityManager();

        /** @var \Rcm\Repository\Domain $domainRepo */
        $domainRepo = $entityManager->getRepository(
            \Rcm\Entity\Domain::class
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
    protected function getDefaultSiteSettings()
    {
        $config = $this->getConfig();

        return $config['rcmAdmin']['defaultSiteSettings'];
    }

    /**
     * @param UserInterface $createdByUser
     *
     * @return mixed
     */
    protected function getDefaultSitePageSettings(UserInterface $createdByUser)
    {
        $myConfig = $this->getDefaultSiteSettings();

        $pagesData = $myConfig['pages'];

        // Set the author for each
        foreach ($pagesData as $key => $pageData) {
            $pagesData[$key]['createdByUserId'] = $createdByUser->getId();
            $pagesData[$key]['createdReason'] = 'Default page creation in ' . get_class($this);
            $pagesData[$key]['author'] = $createdByUser->getName();
        }

        return $pagesData;
    }

    /**
     * @param Site $site
     * @param string $createdByUserId
     * @param string $createdReason
     * @param array $pagesData
     * @param bool $doFlush
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

        $pages = [];
        $pageRevisions = [];

        foreach ($pagesData as $pageName => $pageData) {
            if (empty($pageData['plugins'])) {
                continue;
            }

            $page = $pageRepo->getPageByName($site, $pageData['name']);

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
                $pluginInstance = $pluginInstanceRepo->createPluginInstance(
                    $pluginData,
                    $site,
                    $createdByUserId,
                    $createdReason,
                    null,
                    $doFlush
                );

                $pluginData['pluginInstanceId'] = $pluginInstance->getInstanceId();

                $wrapper = $pluginWrapperRepo->savePluginWrapper(
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

                $entityManager->persist($pageRevision);

                $pageRevisions[] = $pageRevision;
            }
        }

        if ($doFlush) {
            $entityManager->flush($pages);
            $entityManager->flush($pageRevisions);
        }
    }
}
