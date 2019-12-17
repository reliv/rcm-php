<?php


namespace Rcm\SecureRepo;

use Doctrine\ORM\EntityManager;
use Rcm\Acl\AclActions;
use Rcm\Acl\AssertIsAllowed;
use Rcm\Acl\Exception\NotAllowedByBusinessLogicException;
use Rcm\Acl\Exception\NotAllowedBySecurityPropGenerationFailure;
use Rcm\Acl\GetCurrentUser;
use Rcm\Acl\IsAllowed;
use Rcm\Acl\ResourceName;
use Rcm\Acl\SecurityPropertiesProviderInterface;
use Rcm\Acl2\SecurityPropertyConstants;
use Rcm\Entity\Container;
use Rcm\Entity\Page;
use Rcm\Entity\Revision;
use Rcm\Entity\Site;
use Rcm\Exception\InvalidArgumentException;
use Rcm\Exception\PageNotFoundException;
use Rcm\Http\Response;
use Rcm\ImmutableHistory\Page\PageContent;
use Rcm\ImmutableHistory\Page\PageContentFactory;
use Rcm\ImmutableHistory\Page\PageLocator;
use Rcm\ImmutableHistory\Page\RcmPageNameToPathname;
use Rcm\ImmutableHistory\SiteWideContainer\ContainerContent;
use Rcm\ImmutableHistory\SiteWideContainer\SiteWideContainerLocator;
use Rcm\ImmutableHistory\VersionRepositoryInterface;
use Rcm\Repository\Page as PageRepo;
use Rcm\SecurityPropertiesProvider\PageSecurityPropertiesProvider;
use Rcm\Tracking\Exception\TrackingException;
use RcmAdmin\Exception\CannotDuplicateAnUnpublishedPageException;
use RcmMessage\Api\GetCurrentUserId;
use RcmUser\Service\RcmUserService;
use RcmUser\User\Entity\UserInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Stdlib\ResponseInterface;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

/**
 * ALL PAGE MUTATIONS MUST BE DONE THROUGH THIS SERVICE TO ENSURE THEY ARE LOGGED PROPERLY!
 *
 * Class PageSecureRepo
 * @package RcmAdmin\Service
 */
class PageSecureRepo
{
    /**
     * @var \Rcm\Entity\Site
     */
    protected $currentSite;

    /**
     * @var \Rcm\Repository\Page
     */
    protected $pageRepo;

    protected $immuteblePageVersionRepo;

    protected $revisionRepo;

    protected $immutablePageContentFactory;

    protected $rcmPageNameToPathname;

    protected $entityManager;
    protected $immutableSiteWideContainerRepo;
    protected $currentUser;
    protected $assertIsAllowed;

    public function __construct(
        EntityManager $entityManager,
        VersionRepositoryInterface $immuteblePageVersionRepo,
        VersionRepositoryInterface $immutableSiteWideContainerRepo,
        PageContentFactory $immutablePageContentFactory,
        RcmPageNameToPathname $rcmPageNameToPathname,
        Site $currentSite,
        GetCurrentUser $getCurrentUser,
        AssertIsAllowed $assertIsAllowed
    ) {
        $this->currentSite = $currentSite;
        $this->entityManager = $entityManager;
        $this->pageRepo = $entityManager->getRepository(Page::class);
        $this->revisionRepo = $entityManager->getRepository(Revision::class);
        $this->immuteblePageVersionRepo = $immuteblePageVersionRepo;
        $this->immutableSiteWideContainerRepo = $immutableSiteWideContainerRepo;
        $this->immutablePageContentFactory = $immutablePageContentFactory;
        $this->rcmPageNameToPathname = $rcmPageNameToPathname;
        $this->currentUser = $getCurrentUser->__invoke();
        $this->assertIsAllowed = $assertIsAllowed;
    }

    public function findSecurityProperties($data): array
    {
        if (!array_key_exists('siteId', $data)) {
            throw new NotAllowedBySecurityPropGenerationFailure('siteId not passed.');
        }

        /**
         * @var \Rcm\Entity\Site|null $site
         */
        $site = $this->entityManager->getRepository(Site::class)->find($data['siteId']);

        if ($site === null) {
            throw new NotAllowedBySecurityPropGenerationFailure('Site not found.');
        }

        return [
            'type' => SecurityPropertyConstants::TYPE_CONTENT,
            SecurityPropertyConstants::CONTENT_TYPE_KEY => SecurityPropertyConstants::CONTENT_TYPE_PAGE,
            'country' => $site->getCountryIso3()
        ];
    }

    public function assertIsAllowed(string $action, $resourceData)
    {
        $this->assertIsAllowed->__invoke($action, $this->findSecurityProperties($resourceData));
    }

    public function findPagesBySiteId($siteId)
    {
        $this->assertIsAllowed(// Check if we have access to READ pages for the given site
            AclActions::READ,
            ['siteId' => $siteId]
        );

        $site = $this->entityManager->getRepository(Site::class)->find($siteId);

        if (empty($siteId)) {
            return [];
        }

        return $site->getPages();
    }

    public function find($pageId)
    {
        /**
         * @var Page|null $page
         */
        $page = $this->pageRepo->find($pageId);

        if (empty($page)) {
            throw new NotAllowedBySecurityPropGenerationFailure('page not found');
        }

        $this->assertIsAllowed(// Check if we have access to READ the page
            AclActions::READ,
            ['siteId' => $page->getSiteId()]
        );

        return $page;
    }

    /**
     * Creates a new page which starts out "staged" instead of "published".
     *
     * Note: This code was moved pretty mush as-is from "PageController" and that is why it is a bit messy.
     *
     * @param $user
     * @param int $siteId
     * @param string $name
     * @param string $pageType
     * @param $data
     * @return Page
     * @throws TrackingException
     * @throws \Rcm\Exception\PageException
     */
    public function createNewPage(int $siteId, string $name, string $pageType, $data)
    {
        $this->assertIsAllowed(// Check if we have access to CREATE the new page
            AclActions::CREATE,
            ['siteId' => $siteId,]
        );
        $user = $this->currentUser;
        if (empty($user)) {
            throw new TrackingException('A valid user is required in ' . get_class($this));
        }
        $validatedData = $data;
        $pageData = [
            'name' => $name,
            'siteId' => $siteId,
            'pageTitle' => $validatedData['pageTitle'],
            'pageType' => $pageType, // "n" means "normal"
            'siteLayoutOverride' => (
            isset($validatedData['siteLayoutOverride']) ? $validatedData['siteLayoutOverride'] : null
            ),
            'createdByUserId' => $user->getId(),
            'createdReason' => 'New page in ' . get_class($this),
            'author' => $user->getName(),
        ];

        $createdPage = $this->pageRepo->createPage(
            $this->entityManager->find(Site::class, $siteId),
            $pageData
        );

        $this->immuteblePageVersionRepo->createUnpublished(
            new PageLocator(
                $siteId,
                $this->rcmPageNameToPathname->__invoke($createdPage->getName(), $createdPage->getPageType())
            ),
            $this->immutablePageContentFactory->__invoke(
                $createdPage->getPageTitle(),
                $createdPage->getDescription(),
                $createdPage->getKeywords(),
                $this->revisionRepo->find($createdPage->getStagedRevision())->getPluginWrappers()->toArray()
            ),
            $user->getId(),
            __CLASS__ . '::' . __FUNCTION__
        );

        return $createdPage;
    }

    /**
     * Creates a new page from a template. The page starts out "staged" instead of "published".
     *
     * Note: This code was moved pretty mush as-is from "PageController" and that is why it is a bit messy.
     *
     * @return Response|ViewModel
     * @throws TrackingException
     */
    public function createNewPageFromTemplate($data)
    {
        $this->assertIsAllowed(// Check if we have access to CREATE the new page
            AclActions::CREATE,
            ['siteId' => $data['siteId']]
        );
        $user = $this->currentUser;
        if (empty($user)) {
            throw new TrackingException('A valid user is required in ' . get_class($this));
        }
        $validatedData = $data;
        /** @var \Rcm\Entity\Page $page */
        $page = $this->pageRepo->findOneBy(
            [
                'pageId' => $validatedData['page-template'],
                'pageType' => 't'
            ]
        );

        if (empty($page)) {
            throw new NotAllowedBySecurityPropGenerationFailure(
                'No template found for page id: '
                . $validatedData['page-template']
            );
        }

        $this->assertIsAllowed(// Check if we have access to READ the template
            AclActions::READ,
            ['siteId' => $page->getSiteId()]
        );

        $pageData = [
            'name' => $validatedData['name'],
            'pageTitle' => $validatedData['pageTitle'],
            'pageType' => 'n', // "n" means "normal"
            'createdByUserId' => $user->getId(),
            'createdReason' => 'New page from template in ' . get_class($this),
            'author' => $user->getName(),
        ];

        $createdPage = $resultRevisionId = $this->pageRepo->copyPage(
            $this->currentSite,
            $page,
            $pageData
        );

        $this->immuteblePageVersionRepo->createUnpublished(
            new PageLocator(
                $this->currentSite->getSiteId(),
                $this->rcmPageNameToPathname->__invoke($createdPage->getName(), $createdPage->getPageType())
            ),
            $this->immutablePageContentFactory->__invoke(
                $createdPage->getPageTitle(),
                $createdPage->getDescription(),
                $createdPage->getKeywords(),
                $this->revisionRepo->find($resultRevisionId)->getPluginWrappers()->toArray()
            ),
            $user->getId(),
            __CLASS__ . '::' . __FUNCTION__
        );
    }

    /**
     * @param $user
     * @param int $siteId
     * @param string $pageName
     * @param string $pageType
     * @param int $pageRevisionId
     * @return mixed
     * @throws TrackingException
     */
    public function publishPageRevision(
        int $siteId,
        string $pageName,
        string $pageType,
        int $pageRevisionId
    ) {
        $this->assertIsAllowed(// Check if we have access to READ the page we are publishing
            AclActions::READ,
            ['siteId' => $siteId]
        );
        $this->assertIsAllowed(// Check if we have access to UPDATE the page we are publishing
            AclActions::UPDATE,
            ['siteId' => $siteId]
        );

        $user = $this->currentUser;

        if (empty($user)) {
            throw new TrackingException('A valid user is required in ' . get_class($this));
        }

        $page = $this->pageRepo->publishPageRevision(
            $siteId,
            $pageName,
            $pageType,
            $pageRevisionId,
            $user->getId(),
            'Publish page in ' . get_class($this)
        );

        $this->immuteblePageVersionRepo->publish(
            new PageLocator(
                $siteId,
                $this->rcmPageNameToPathname->__invoke($pageName, $pageType)
            ),
            $this->immutablePageContentFactory->__invoke(
                $page->getPageTitle(),
                $page->getDescription(),
                $page->getKeywords(),
                $page->getPublishedRevision()->getPluginWrappers()->toArray()
            ),
            $user->getId(),
            __CLASS__ . '::' . __FUNCTION__
        );

        return $page;
    }

    /**
     * savePageDraft
     *
     * @return Response|ResponseInterface
     *
     * @throws TrackingException
     */
    public function savePageDraft(
        string $pageName,
        string $pageType,
        $data,
        $urlToPageFunction,
        int $originalRevisionId
    ) {
        $site = $this->currentSite;
        $this->assertIsAllowed(// Check if we have access to UPDATE the page we are saving
            AclActions::UPDATE,
            ['siteId' => $site->getSiteId()]
        );

        $user = $this->currentUser;

        if (empty($user)) {
            throw new TrackingException('A valid user is required in ' . get_class($this));
        }

        self::prepSaveData($data);

        $result = $this->pageRepo->savePage(
            $site,
            $pageName,
            $originalRevisionId,
            $pageType,
            $data,
            $user->getId(),
            'Save existing page in ' . get_class($this),
            $user->getName()
        );

        /**
         * If the pageRepo deterimins there was no change, it saves nothing a returns and empty $resultRevisionId.
         */
        $savedANewVersion = !empty($result['newPageRevisionId']);

        if ($savedANewVersion) {
            $resultPageRevisionId = $result['newPageRevisionId'];
            /**
             * @var Page
             */
            $page = $this->pageRepo->findOneBy([
                'site' => $site,
                'name' => $pageName,
                'pageType' => $pageType
            ]);

            $this->immuteblePageVersionRepo->createUnpublished(
                new PageLocator(
                    $site->getSiteId(),
                    $this->rcmPageNameToPathname->__invoke($pageName, $pageType)
                ),
                $this->immutablePageContentFactory->__invoke(
                    $page->getPageTitle(),
                    $page->getDescription(),
                    $page->getKeywords(),
                    $this->revisionRepo->find($resultPageRevisionId)->getPluginWrappers()->toArray()
                ),
                $user->getId(),
                __CLASS__ . '::' . __FUNCTION__
            );
        }

        /**
         * @var $container Container
         */
        foreach ($result['modifiedSiteWideContainers'] as $revisionId => $container) {
            $this->immutableSiteWideContainerRepo->publish(
                new SiteWideContainerLocator($container->getSiteId(), $container->getName()),
                new ContainerContent(
                    $this->immutablePageContentFactory->pluginWrappersToFlatBlockInstances(
                        $this->revisionRepo->find($revisionId)->getPluginWrappers()->toArray()
                    )
                ),
                $user->getId(),
                __CLASS__ . '::' . __FUNCTION__
            );
        }

        if ($savedANewVersion) {
            $return['redirect'] = $urlToPageFunction(
                $pageName,
                $pageType,
                $resultPageRevisionId
            );
        } else {
            $return['redirect'] = $urlToPageFunction(
                $pageName,
                $pageType,
                $originalRevisionId
            );
        }

        return $return;
    }

    public function depublishPage(Page $page)
    {
        $this->assertIsAllowed(// Check if we have access to DELETE the page we are deleting
            AclActions::DELETE,
            ['siteId' => $page->getSite()->getSiteId()]
        );

        $user = $this->currentUser;

        if (empty($user)) {
            throw new TrackingException('A valid user is required in ' . get_class($this));
        }

        $originalPageNameBeforeDeletionChangedIt = $page->getName();
        $originalPageTypeBeforeDeletionChangedIt = $page->getPageType();

        $result = $this->pageRepo->setPageDeleted(
            $page,
            $user->getId(),
            'Delete page in ' . get_class($this)
        );

        $this->immuteblePageVersionRepo->depublish(
            new PageLocator(
                $page->getSiteId(),
                $this->rcmPageNameToPathname->__invoke(
                    $originalPageNameBeforeDeletionChangedIt,
                    $originalPageTypeBeforeDeletionChangedIt
                )
            ),
            $user->getId(),
            __CLASS__ . '::' . __FUNCTION__
        );
    }

    public function duplicatePage(
        Page $page,
        $destinationSiteId,
        $destinationPageName,
        $destinationPageType = null
    ): Page {
        $siteId = $page->getSite()->getSiteId();
        $this->assertIsAllowed(// Check if we have access to READ the page we are copying from
            AclActions::READ,
            ['siteId' => $siteId]
        );

        $user = $this->currentUser;

        if (empty($user)) {
            throw new TrackingException('A valid user is required in ' . get_class($this));
        }

        if (empty($page->getPublishedRevision())) {
            throw new CannotDuplicateAnUnpublishedPageException();
        }

        /**
         * Site | null
         */
        $destinationSite = $this->entityManager->getRepository(Site::class)->find($destinationSiteId);
        if (!$destinationSite) {
            throw new NotAllowedBySecurityPropGenerationFailure(
                'Site could not be found for id "' . $destinationSiteId . '")'
            );
        }

        $this->assertIsAllowed(// Check if we have access to CREATE the page we are copying to
            AclActions::CREATE,
            ['siteId' => $destinationSiteId,]
        );

        $destinationPage = new Page(
            $user->getId(),
            'New page in ' . get_class($this)
        );

        $destinationPage->populate($page->toArray());

        $destinationPage->setSite($destinationSite);
        $destinationPage->setName($destinationPageName);
        $destinationPage->setAuthor($user->getName());
        $destinationPage->setModifiedByUserId($user->getId());

        if ($destinationPageType !== null) {
            $destinationPage->setPageType($destinationPageType);
        }

        $destinationPage = $this->pageRepo->copyPage(
            $destinationSite,
            $page,
            $destinationPage->toArray(),
            null,
            true
        );

        $this->immuteblePageVersionRepo->duplicateBc(
            new PageLocator(
                $page->getSiteId(),
                $this->rcmPageNameToPathname->__invoke($page->getName(), $page->getPageType())
            ),
            new PageLocator(
                $destinationSite->getSiteId(),
                $this->rcmPageNameToPathname->__invoke($destinationPage->getName(), $destinationPage->getPageType())
            ),
            $this->immutablePageContentFactory->__invoke(
                $page->getPageTitle(),
                $page->getDescription(),
                $page->getDescription(),
                $page->getPublishedRevision()->getPluginWrappers()->toArray()
            ),
            $user->getId(),
            __CLASS__ . '::' . __FUNCTION__
        );

        return $destinationPage;
    }

    /**
     * Note: If the new version has different location information, this will be logged in immutable history
     * as an "publish" action at the original location then a "relocate" action right afterward to the new location.
     *
     * @param $user
     * @param Page $page
     * @param $data
     */
    public function updatePublishedVersionOfPage(Page $page, $data)
    {
        $sourceSite = $page->getSite();
        if (array_key_exists('site', $data) || array_key_exists('siteId', $data)) {
            //Disallow this to make ACL lookups simpler.
            throw new NotAllowedByBusinessLogicException('Cannot change site of page.');
        }

        $this->assertIsAllowed(// Check if we have access to READ the page we are updating
            AclActions::READ,
            ['siteId' => $sourceSite->getSiteId()]
        );

        $this->assertIsAllowed(// Check if we have access to UPDATE the page we are updating
            AclActions::UPDATE,
            ['siteId' => $sourceSite->getSiteId()]
        );

        $user = $this->currentUser;

        $originalLocator = new PageLocator(
            $this->currentSite->getSiteId(),
            $this->rcmPageNameToPathname->__invoke($page->getName(), $page->getPageType())
        );
        $updatedPage = $this->pageRepo->updatePage($page, $data);
        $updatedLocator = new PageLocator(
            $this->currentSite->getSiteId(),
            $this->rcmPageNameToPathname->__invoke($updatedPage->getName(), $updatedPage->getPageType())
        );
        $publishedRevision = $updatedPage->getPublishedRevision();

        if (is_object($publishedRevision)) {
            //This code path is the most commonly traveled.
            $pluginWrapperData = $publishedRevision->getPluginWrappers()->toArray();
        } else {
            //We wind up here if someone edits the properties of an unpublished page.
            $pluginWrapperData = [];
        }

        $this->immuteblePageVersionRepo->publish(
            $originalLocator,
            $this->immutablePageContentFactory->__invoke(
                $updatedPage->getPageTitle(),
                $updatedPage->getDescription(),
                $updatedPage->getKeywords(),
                $pluginWrapperData,
                $updatedPage->allowsPublicReadAccess(),
                $updatedPage->getReadAccessGroups()
            ),
            $user->getId(),
            __CLASS__ . '::' . __FUNCTION__
        );
        if (json_encode($originalLocator->toArray()) !== json_encode($updatedLocator->toArray())) {
            $this->immuteblePageVersionRepo->relocate(
                $originalLocator,
                $updatedLocator,
                $user->getId(),
                __CLASS__ . '::' . __FUNCTION__
            );
        }
    }

    /**
     * Prep and validate data array to save
     *
     * @param $data
     *
     * @throws InvalidArgumentException
     */
    protected static function prepSaveData(&$data)
    {
        if (!is_array($data)) {
            $data = [];
        }

        ksort($data);

        $data['containers'] = [];
        $data['pageContainer'] = [];

        if (empty($data['plugins'])) {
            throw new InvalidArgumentException(
                'Save Data missing plugins .
                Please make sure the data you\'re attempting to save is correctly formatted.
            '
            );
        }

        foreach ($data['plugins'] as &$plugin) {
            self::cleanSaveData($plugin['saveData']);

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

            $plugin['rank'] = (int)$plugin['rank'];
            $plugin['rowNumber'] = (int)$plugin['rowNumber'];
            $plugin['columnClass'] = (string)$plugin['columnClass'];

            $plugin['containerName'] = $plugin['containerId'];

            if ($plugin['containerType'] == 'layout') {
                $data['containers'][$plugin['containerId']][] = &$plugin;
            } else {
                $data['pageContainer'][] = &$plugin;
            }
        }
    }

    /**
     * Save data clean up.
     *
     * @param $data
     */
    protected static function cleanSaveData(
        &$data
    ) {
        if (empty($data)) {
            return;
        }

        if (is_array($data)) {
            ksort($data);

            foreach ($data as &$arrayData) {
                self::cleanSaveData($arrayData);
            }

            return;
        }

        if (is_string($data)) {
            $data = trim(
                str_replace(
                    [
                        "\n",
                        "\t",
                        "\r"
                    ],
                    "",
                    $data
                )
            );
        }

        return;
    }
}
