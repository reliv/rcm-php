<?php

namespace Rcm\SecureRepo;

use Doctrine\ORM\EntityManager;
use Rcm\Acl\AclActions;
use Rcm\Acl\AssertIsAllowed;
use Rcm\Acl\Exception\NotAllowedBySecurityPropGenerationFailure;
use Rcm\Acl\GetCurrentUser;
use Rcm\Acl\NotAllowedException;
use Rcm\Acl\SecurityPropertiesProviderInterface;
use Rcm\Entity\Container;
use Rcm\Entity\Country;
use Rcm\Entity\Domain;
use Rcm\Entity\Language;
use Rcm\Entity\Page;
use Rcm\Entity\Site;
use Rcm\ImmutableHistory\Page\PageContentFactory;
use Rcm\ImmutableHistory\Site\SiteContent;
use Rcm\ImmutableHistory\Site\SiteLocator;
use Rcm\ImmutableHistory\SiteWideContainer\ContainerContent;
use Rcm\ImmutableHistory\SiteWideContainer\SiteWideContainerLocator;
use Rcm\ImmutableHistory\VersionRepositoryInterface;
use Rcm\Page\PageTypes\PageTypes;
use Rcm\Tracking\Exception\TrackingException;
use Rcm\Tracking\Model\Tracking;
use RcmAdmin\InputFilter\SiteInputFilter;
use RcmUser\Service\RcmUserService;
use RcmUser\User\Entity\UserInterface;
use Rcm\Exception\InputFilterFoundInvalidDataException;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Interop\Container\ContainerInterface;
use Rcm\Acl\ResourceName;
use Rcm\Http\Response;
use Rcm\RequestContext\RequestContext;
use Rcm\View\Model\ApiJsonModel;
use Rcm\Http\NotAllowedResponseJsonZf2;
use Zend\Paginator\Paginator;
use Zend\View\Model\JsonModel;

class SiteSecureRepo
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

    protected $immutableSiteWideContainerRepo;

    protected $pageContentFactory;
    protected $getCurrentUser;
    protected $siteSecurityPropertiesProvider;
    protected $assertIsAllowed;
    protected $currentUser;

    public function __construct(
        $config,
        EntityManager $entityManager,
        RcmUserService $rcmUserService,
        PageSecureRepo $pageMutationService,
        VersionRepositoryInterface $siteVersionRepo,
        VersionRepositoryInterface $immutableSiteWideContainerRepo,
        PageContentFactory $pageContentFactory,
        GetCurrentUser $getCurrentUser,
        SecurityPropertiesProviderInterface $siteSecurityPropertiesProvider,
        AssertIsAllowed $assertIsAllowed
    ) {
        $this->config = $config;
        $this->entityManager = $entityManager;
        $this->rcmUserService = $rcmUserService;
        $this->pageMutationService = $pageMutationService;
        $this->siteVersionRepo = $siteVersionRepo;
        $this->immutableSiteWideContainerRepo = $immutableSiteWideContainerRepo;
        $this->pageContentFactory = $pageContentFactory;
        $this->currentUser = $getCurrentUser->__invoke();
        $this->siteSecurityPropertiesProvider = $siteSecurityPropertiesProvider;
        $this->assertIsAllowed = $assertIsAllowed;
    }

    /**
     * Note: this code was moved here durring the ACL2 project from ApiAdminManageSitesController
     *
     * Note: this function will omit any sites the user doesn't have access to read from the returned list
     *
     * @return mixed|JsonModel
     */
    public function getList($searchQuery, $page, $pageSize)
    {
        /** @var \Doctrine\ORM\EntityManagerInterface $entityManager */
        $entityManager = $this->getEntityManager();

        /** @var \Rcm\Repository\Site $siteRepo */
        $siteRepo = $entityManager->getRepository(\Rcm\Entity\Site::class);
        $createQueryBuilder = $siteRepo->createQueryBuilder('site')
            ->select('site')
            ->leftJoin('site.domain', 'domain')
            ->leftJoin('site.country', 'country')
            ->leftJoin('site.language', 'language');
        // @todo This is broken in doctrine 1.* with MySQL 5.7
        //->orderBy('domain.domain', 'ASC');

        $query = $createQueryBuilder->getQuery();

        if ($searchQuery) {
            $createQueryBuilder->where(
                $createQueryBuilder->expr()->like(
                    'domain.domain',
                    ':searchQuery'
                )
            );
            $query = $createQueryBuilder->getQuery();
            $query->setParameter('searchQuery', $searchQuery . '%');
        }

        $adaptor = new DoctrinePaginator(
            new ORMPaginator($query)
        );
        $paginator = new Paginator($adaptor);
        $paginator->setDefaultItemCountPerPage(10);

        if ($page) {
            $paginator->setCurrentPageNumber($page);
        }

        if ($pageSize) {
            $paginator->setItemCountPerPage($pageSize);
        }

        $sitesObjects = $paginator->getCurrentItems();

        $sites = [];

        /** @var \Rcm\Entity\Site $site */
        foreach ($sitesObjects as $site) {
            try {
                $this->assertIsAllowed->__invoke(// Check if we have access to READ the site
                    AclActions::READ,
                    $this->siteSecurityPropertiesProvider->findSecurityPropertiesFromCreationData([
                        'countryIso3' => $site->getCountryIso3()
                    ])
                );
                $sites[] = $site->toArray();
            } catch (NotAllowedException $e) {
                continue; //If the user is not allowed to read the site, omit it from the returned list.
            }
        }

        $list['items'] = $sites;
        $list['itemCount'] = $paginator->getTotalItemCount();
        $list['pageCount'] = $paginator->count();
        $list['currentPage'] = $paginator->getCurrentPageNumber();

        return $list;
    }

    /**
     * Note: this code was moved here durring the ACL2 project from ApiAdminManageSitesController
     *
     * @param mixed $id
     *
     * @return mixed|ApiJsonModel|\Zend\Stdlib\ResponseInterface
     */
    public function get($id): Site
    {
        // get default site data - kinda hacky, but keeps us to one controller
        if ($id == 'default') {
            $data = $this->getDefaultSiteValues();

            $user = $this->currentUser;

            if ($user === null) {
                throw new NotAllowedBySecurityPropGenerationFailure('user cannot be null');
            }

            $userId = $user->getId();

            $site = new Site(
                $userId,
                'Get default site values in ' . get_class($this)
            );

            $site->populate($data);

            $this->assertIsAllowed->__invoke(// Check if we have access to READ the site
                AclActions::READ,
                $this->siteSecurityPropertiesProvider->findSecurityPropertiesFromCreationData([
                    'countryIso3' => $site->getCountryIso3()
                ])
            );

            return $site;
        }

        // get current site data - kinda hacky, but keeps us to one controller
        if ($id == 'current') {
            $site = $this->getCurrentSite(); //@TODO missing getCurrentSite //@TODO missing getCurrentSite

            $this->assertIsAllowed->__invoke(// Check if we have access to READ the site
                AclActions::READ,
                $this->siteSecurityPropertiesProvider->findSecurityPropertiesFromCreationData([
                    'countryIso3' => $site->getCountryIso3()
                ])
            );

            return $site;
        }

        /** @var \Rcm\Repository\Site $siteRepo */
        $siteRepo = $this->getEntityManager()->getRepository(
            \Rcm\Entity\Site::class
        );

        $site = $siteRepo->find($id);

        if ($site === null) {
            throw new NotAllowedBySecurityPropGenerationFailure('site not found');
        }

        $this->assertIsAllowed->__invoke(// Check if we have access to READ the site
            AclActions::READ,
            $this->siteSecurityPropertiesProvider->findSecurityPropertiesFromCreationData([
                'countryIso3' => $site->getCountryIso3()
            ])
        );

        return $site;
    }

    /**
     * Note: this code was moved here durring the ACL2 project from ApiAdminManageSitesController
     *
     * @param $data
     * @return ApiJsonModel
     * @throws TrackingException
     */
    public function createSingleFromArray($data)
    {
        $this->assertIsAllowed->__invoke(// Check if we have access to CREATE the new site
            AclActions::CREATE,
            $this->siteSecurityPropertiesProvider->findSecurityPropertiesFromCreationData([
                'countryIso3' => $data['countryId']
            ])
        );

        $inputFilter = new SiteInputFilter();
        $inputFilter->setData($data);

        if (!$inputFilter->isValid()) {
            throw new InputFilterFoundInvalidDataException(
                'Some values are missing or invalid.',
                $inputFilter->getMessages()
            );
        }

        $data = $inputFilter->getValues();

        $user = $this->currentUser;

        if ($user === null) {
            throw new NotAllowedBySecurityPropGenerationFailure('user cannot be null');
        }

        $userId = $user->getId();

        $data = $this->prepareSiteData($data);
        /** @var \Rcm\Repository\Domain $domainRepo */
        $domainRepo = $this->getEntityManager()->getRepository(
            \Rcm\Entity\Domain::class
        );

        $data['domain'] = $domainRepo->createDomain(
            $data['domainName'],
            $userId,
            'Create new domain in ' . get_class($this)
        );

        /** @var \Rcm\Entity\Site $newSite */
        $newSite = new Site(
            $userId,
            'Create new site in ' . get_class($this)
        );

        $newSite->populate($data);
        // make sure we don't have a siteId
        $newSite->setSiteId(null);

        $newSite = $this->createSite($newSite);

        return $newSite;
    }

    /**
     * NoteL this code was moved here durring the ACL2 project from ApiAdminManageSitesController
     *
     * update @todo - allow update of all properties and filter input
     *
     * @param mixed $siteId
     * @param mixed $data
     *
     * @return mixed|JsonModel
     * @throws \Exception
     */
    public function update($siteId, $data)
    {
        if (!is_array($data)) {
            throw new NotAllowedBySecurityPropGenerationFailure('data is not an array');
        }

        /** @var \Doctrine\ORM\EntityManager $entityManager */
        $entityManager = $this->getEntityManager();

        /** @var \Rcm\Repository\Site $siteRepo */
        $siteRepo = $entityManager->getRepository(\Rcm\Entity\Site::class);

        if (!$siteRepo->isValidSiteId($siteId)) {
            throw new NotAllowedBySecurityPropGenerationFailure('invalid siteId');
        }

        $this->assertIsAllowed->__invoke(// Check if we have access to UPDATE the new site
            AclActions::UPDATE,
            $this->siteSecurityPropertiesProvider->findSecurityPropertiesFromCreationData([
                'countryIso3' => $data['countryId']
            ])
        );

        /** @var \Rcm\Entity\Site $site */
        $site = $siteRepo->findOneBy(['siteId' => $siteId]);

        $newStatus = $site->getStatus();

        if ($data['status'] == 'D') {
            $newStatus = 'D';
        }
        if ($data['status'] == 'A') {
            $newStatus = 'A';
        }

        $site->setStatus($newStatus);

        $user = $this->currentUser;

        if ($user === null) {
            throw new NotAllowedBySecurityPropGenerationFailure('user cannot be null');
        }

        $userId = $user->getId();

        $site->setModifiedByUserId(
            $userId,
            "Update site status to {$newStatus} in " . get_class($this)
        );

        $entityManager->persist($site);
        $entityManager->flush($site);

        $this->siteVersionRepo->publish(
            new SiteLocator($site->getDomainName()),
            $this->siteToImmutableSiteContent($site),
            $this->getCurrentUserTracking()->getId(),
            __CLASS__ . '::' . __FUNCTION__,
            $site->getSiteId()
        );

        return $site;
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
        $user = $this->getCurrentUserBc();

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
        $this->assertIsAllowed->__invoke(// Check if we have access to CREATE the new site
            AclActions::CREATE,
            $this->siteSecurityPropertiesProvider->findSecurityPropertiesFromCreationData([
                'countryIso3' => $newSite->getCountryIso3()
            ])
        );
        $newSite = $this->prepareNewSite($newSite);

        $entityManager = $this->getEntityManager();

        /** @var \Rcm\Repository\Page $pageRepo */
        $pageRepo = $entityManager->getRepository(\Rcm\Entity\Page::class);

        $user = $this->getCurrentUserTracking();

        $entityManager->persist($newSite);
        $entityManager->flush($newSite);

        $this->siteVersionRepo->publish(
            new SiteLocator($newSite->getDomainName()),
            $this->siteToImmutableSiteContent($newSite),
            $this->getCurrentUserTracking()->getId(),
            __CLASS__ . '::' . __FUNCTION__,
            $newSite->getSiteId()
        );

        foreach ($this->getDefaultSitePageSettings($user) as $name => $pageData) {
            $createdPage = $this->pageMutationService->createNewPage(
                $newSite->getSiteId(),
                $pageData['name'],
                $pageData['pageType'],
                //Axosoft ticket #22776 was created to fix this @TODO. it is a pretty low priority issue
                $pageData //@TODO this is probably not in the correct format for plugins/blocks/pluginWrapper data
            );
            $this->pageMutationService->publishPageRevision(
                $newSite->getSiteId(),
                $pageData['name'],
                $pageData['pageType'],
                $createdPage->getStagedRevision()->getRevisionId()
            );
        }

        return $newSite;
    }

//    protected function urlToPage(Page $page){
//        if ($page->getPageType() !== PageTypes::NORMAL || $pageRevision !== null) {
//            throw new \Exception('Unsupported Case');
//
//            return '/' . $page->getName();
//        }
//    }

    public function duplicateAndUpdate(
        Site $existingSite,
        string $newDomainName,
        $data = []
    ) {

        $entityManager = $this->getEntityManager();

        $this->assertIsAllowed->__invoke(// Check if we have access to READ the source site
            AclActions::READ,
            $this->siteSecurityPropertiesProvider->findSecurityPropertiesFromCreationData([
                'countryIso3' => $existingSite->getCountryIso3()
            ])
        );

        if (!array_key_exists('countryId', $data)) {
            throw new NotAllowedBySecurityPropGenerationFailure(
                'No countryId provided in copySiteAndPopulate data'
            );
        }

        $this->assertIsAllowed->__invoke(// Check if we have access to CREATE the new site
            AclActions::CREATE,
            $this->siteSecurityPropertiesProvider->findSecurityPropertiesFromCreationData([
                'countryIso3' => $data['countryId']
            ])
        );

        $data = $this->prepareSiteData($data);
        /** @var \Rcm\Repository\Domain $domainRepo */
        $domainRepo = $this->entityManager->getRepository(
            \Rcm\Entity\Domain::class
        );

        $user = $this->currentUser;

        if ($user === null) {
            throw new NotAllowedBySecurityPropGenerationFailure('user cannot be null');
        }

        $userId = $user->getId();

        $domain = $domainRepo->createDomain(
            $newDomainName,
            $userId,
            'Create new domain in ' . get_class($this),
            null,
            true
        );

        try {
            $copySite = $this->copySite($existingSite, $domain);
            $copySite->populate($data);
        } catch (\Exception $e) {
            //If something went wrong, delete the orphaned domain we created.
            $this->entityManager->remove($domain);
            $this->entityManager->flush($domain);
        }

        $this->siteVersionRepo->publish(
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
    protected function getCurrentUserBc()
    {
        return $this->currentUser;
    }

    /**
     * @return \RcmUser\User\Entity\UserInterface
     * @throws TrackingException
     */
    protected function getCurrentUserTracking()
    {
        $user = $this->getCurrentUserBc();

        if (empty($user)) {
            throw new TrackingException('A valid user is required in ' . get_class($this));
        }

        return $user;
    }

    /**
     * Warning: This does not enforce ACL and MUST REMAIN A PRIVATE METHOD!
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

        $user = $this->currentUser;

        if ($user === null) {
            throw new NotAllowedBySecurityPropGenerationFailure('user cannot be null');
        }

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

//        $entityManager->flush($domain);
//        $entityManager->flush($copySite);

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

        foreach ($containers as $container) {
            $this->immutableSiteWideContainerRepo->duplicateBc(
                new SiteWideContainerLocator($existingSite->getSiteId(), $container->getName()),
                new SiteWideContainerLocator($copySite->getSiteId(), $container->getName()),
                new ContainerContent($this->pageContentFactory->pluginWrappersToFlatBlockInstances(
                    $container->getPublishedRevision()->getPluginWrappers()->toArray()
                )),
                $this->getCurrentUserTracking()->getId(),
                __CLASS__ . '::' . __FUNCTION__
            );
        }


        /** @var Page $page */
        foreach ($existingSite->getPages() as $page) {
            if (empty($page->getPublishedRevision())) {
                continue; //Don't copy unpublished pages
            }
            $destinationPage = $this->pageMutationService->duplicatePage(
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

    public function changeSiteDomainName(Site $site, $newHost, string $userId)
    {
        $this->assertIsAllowed->__invoke(// Check if we have access to UPDATE the site
            AclActions::UPDATE,
            $this->siteSecurityPropertiesProvider->findSecurityPropertiesFromCreationData([
                'countryIso3' => $site->getCountryIso3()
            ])
        );

        $domainObject = $site->getDomain();
        $oldHost = $domainObject->getDomainName();
        $oldLocator = new SiteLocator($oldHost);

        /**
         * For BC support, ensure we have a published version of the site in history
         * before trying to rename it. This wouldn't be needed if all sites were
         * created after the immutable history system was launched.
         */
        $this->siteVersionRepo->publish(
            $oldLocator,
            $this->siteToImmutableSiteContent($site),
            $userId,
            __CLASS__ . '::' . __FUNCTION__,
            $site->getSiteId()
        );

        //Change the domain name in RCM core
        $domainObject->setDomainName($newHost);
        $this->entityManager->flush($domainObject);

        //Change the domain name in the immutable history system
        $this->siteVersionRepo->relocate(
            $oldLocator,
            new SiteLocator($newHost),
            $userId,
            __CLASS__ . '::' . __FUNCTION__
        );
    }
}
