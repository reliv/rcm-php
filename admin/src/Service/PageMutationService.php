<?php


namespace RcmAdmin\Service;

use Doctrine\ORM\EntityManager;
use Rcm\Entity\Revision;
use Rcm\Acl\ResourceName;
use Rcm\Entity\Page;
use Rcm\Entity\Site;
use Rcm\Exception\InvalidArgumentException;
use Rcm\Exception\PageNotFoundException;
use Rcm\Http\Response;
use Rcm\ImmutableHistory\Page\PageContent;
use Rcm\ImmutableHistory\Page\PageContentFactory;
use Rcm\ImmutableHistory\Page\PageLocator;
use Rcm\ImmutableHistory\Page\RcmPageNameToPathname;
use Rcm\ImmutableHistory\VersionRepositoryInterface;
use Rcm\Repository\Page as PageRepo;
use Rcm\Tracking\Exception\TrackingException;
use RcmUser\Service\RcmUserService;
use RcmUser\User\Entity\UserInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Stdlib\ResponseInterface;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

/**
 * ALL PAGE MUTATIONS MUST BE DONE THROUGH THIS SERVICE TO ENSURE THEY ARE LOGGED PROPERLY!
 *
 * Class PageMutationService
 * @package RcmAdmin\Service
 */
class PageMutationService
{
    /**
     * @var \Rcm\Entity\Site
     */
    protected $currentSite;

    /**
     * @var \Rcm\Repository\Page
     */
    protected $pageRepo;

    /**
     * @var \Zend\View\Model\ViewModel
     */
    protected $view;

    /**
     * @var RcmUserService
     */
    protected $rcmUserService;

    protected $immuteblePageVersionRepo;

    protected $revisionRepo;

    protected $immutablePageContentFactory;

    protected $rcmPageNameToPathname;

    protected $entityManager;

    public function __construct(
        Site $currentSite,
        RcmUserService $rcmUserService,
        EntityManager $entityManager,
        VersionRepositoryInterface $immuteblePageVersionRepo,
        PageContentFactory $immutablePageContentFactory,
        RcmPageNameToPathname $rcmPageNameToPathname
    ) {
        $this->currentSite = $currentSite;
        $this->entityManager = $entityManager;
        $this->pageRepo = $entityManager->getRepository(Page::class);
        $this->revisionRepo = $entityManager->getRepository(Revision::class);
        $this->rcmUserService = $rcmUserService;
        $this->immuteblePageVersionRepo = $immuteblePageVersionRepo;
        $this->immutablePageContentFactory = $immutablePageContentFactory;
        $this->rcmPageNameToPathname = $rcmPageNameToPathname;

        $this->view = new ViewModel();
        $this->view->setTerminal(true);
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
    public function createNewPage($user, int $siteId, string $name, string $pageType, $data)
    {
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

        $this->immuteblePageVersionRepo->createUnpublishedFromNothing(
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
    public function createNewPageFromTemplate($user, $data)
    {
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
            throw new PageNotFoundException(
                'No template found for page id: '
                . $validatedData['page-template']
            );
        }

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

        $this->immuteblePageVersionRepo->createUnpublishedFromNothing(
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
     * @param $urlToPageFunction
     * @return mixed
     * @throws TrackingException
     */
    public function publishPageRevision(
        $user,
        int $siteId,
        string $pageName,
        string $pageType,
        int $pageRevisionId,
        $urlToPageFunction
    ) {
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

        $this->immuteblePageVersionRepo->publishFromNothing(
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

        return $urlToPageFunction(
            $pageName,
            $pageType
        );
    }

    /**
     * savePageDraft
     *
     * @return Response|ResponseInterface
     *
     * @throws TrackingException
     */
    public function savePageDraft($user, $pageName, $pageRevision, $pageType, $data, $urlToPageFunction)
    {
        if (empty($user)) {
            throw new TrackingException('A valid user is required in ' . get_class($this));
        }

        $this->prepSaveData($data);

        $resultRevisionId = $this->pageRepo->savePage(
            $this->currentSite,
            $pageName,
            $pageRevision,
            $pageType,
            $data,
            $user->getId(),
            'Save existing page in ' . get_class($this),
            $user->getName()
        );

        /**
         * If the pageRepo deterimins there was no change, it saves nothing a returns and empty $resultRevisionId.
         */
        $savedANewVersion = !empty($resultRevisionId);

        if ($savedANewVersion) {
            /**
             * @var Page
             */
            $page = $this->pageRepo->findOneBy([
                'site' => $this->currentSite,
                'name' => $pageName,
                'pageType' => $pageType
            ]);

            $this->immuteblePageVersionRepo->createUnpublishedFromNothing(
                new PageLocator(
                    $this->currentSite->getSiteId(),
                    $this->rcmPageNameToPathname->__invoke($pageName, $pageType)
                ),
                $this->immutablePageContentFactory->__invoke(
                    $page->getPageTitle(),
                    $page->getDescription(),
                    $page->getKeywords(),
                    $this->revisionRepo->find($resultRevisionId)->getPluginWrappers()->toArray()
                ),
                $user->getId(),
                __CLASS__ . '::' . __FUNCTION__
            );
        }

        if ($savedANewVersion) {
            $return['redirect'] = $urlToPageFunction(
                $pageName,
                $pageType,
                $resultRevisionId
            );
        } else {
            $return['redirect'] = $urlToPageFunction(
                $pageName,
                $pageType,
                $pageRevision
            );
        }

        return $return;
    }

    public function depublishPage($user, Page $page)
    {
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
        UserInterface $user,
        Page $page,
        $destinationSiteId,
        $destinationPageName,
        $desitnationPageType = null
    ): Page {
        if (empty($user)) {
            throw new TrackingException('A valid user is required in ' . get_class($this));
        }

        /**
         * Site | null
         */
        $destinationSite = $this->entityManager->find(Site::class, $destinationSiteId);
        if (!$destinationSite) {
            throw new \Exception('Site could not be found for id "' . $destinationSiteId . '")');
        }
        $destinationPage = new Page(
            $user->getId(),
            'New page in ' . get_class($this)
        );

        $destinationPage->populate($page->toArray());

        $destinationPage->setSite($destinationSite);
        $destinationPage->setName($destinationPageName);
        $destinationPage->setAuthor($user->getName());
        $destinationPage->setModifiedByUserId($user->getId());

        if ($desitnationPageType !== null) {
            $destinationPage->setPageType($desitnationPageType);
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
    public function updatePublishedVersionOfPage($user, Page $page, $data)
    {
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

        $this->immuteblePageVersionRepo->publishFromNothing(
            $originalLocator,
            $this->immutablePageContentFactory->__invoke(
                $updatedPage->getPageTitle(),
                $updatedPage->getDescription(),
                $updatedPage->getKeywords(),
                $pluginWrapperData
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
    protected function prepSaveData(&$data)
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
            $this->cleanSaveData($plugin['saveData']);

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
    protected function cleanSaveData(
        &$data
    ) {
        if (empty($data)) {
            return;
        }

        if (is_array($data)) {
            ksort($data);

            foreach ($data as &$arrayData) {
                $this->cleanSaveData($arrayData);
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
