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

    public function __construct(
        Site $currentSite,
        RcmUserService $rcmUserService,
        EntityManager $entityManager,
        VersionRepositoryInterface $immuteblePageVersionRepo,
        PageContentFactory $immutablePageContentFactory,
        RcmPageNameToPathname $rcmPageNameToPathname
    ) {
        $this->currentSite = $currentSite;
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
     * @return Response|ViewModel
     * @throws TrackingException
     */
    public function createNewPage($user, $data)
    {
        if (empty($user)) {
            throw new TrackingException('A valid user is required in ' . get_class($this));
        }
        $validatedData = $data;
        $pageData = [
            'name' => $validatedData['url'],
            'pageTitle' => $validatedData['title'],
            'pageType' => 'n', // "n" means "normal"
            'siteLayoutOverride' => $validatedData['main-layout'],
            'createdByUserId' => $user->getId(),
            'createdReason' => 'New page in ' . get_class($this),
            'author' => $user->getName(),
        ];

        $resultRevisionId = $this->pageRepo->createPage(
            $this->currentSite,
            $pageData
        );

        $this->immuteblePageVersionRepo->createUnpublishedFromNothing(
            new PageLocator($this->currentSite->getSiteId(),
                $this->rcmPageNameToPathname->__invoke($pageData['name'], $pageData['pageType'])),
            $this->immutablePageContentFactory->__invoke(
                $pageData['pageTitle'],
                '', //@TODO is this right?
                '', //@TODO is this right?
                $this->revisionRepo->find($resultRevisionId)->getPluginWrappers()->toArray()
            ),
            $user->getId(),
            __CLASS__ . '::' . __FUNCTION__
        );
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
            'name' => $validatedData['url'],
            'pageTitle' => $validatedData['title'],
            'pageType' => 'n', // "n" means "normal"
            'createdByUserId' => $user->getId(),
            'createdReason' => 'New page from template in ' . get_class($this),
            'author' => $user->getName(),
        ];

        $resultRevisionId = $this->pageRepo->copyPage(
            $this->currentSite,
            $page,
            $pageData
        );

        $this->immuteblePageVersionRepo->createUnpublishedFromNothing(
            new PageLocator($this->currentSite->getSiteId(),
                $this->rcmPageNameToPathname->__invoke($pageData['name'], $pageData['pageType'])),
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

    /**
     * @TODO stop passing $urlToPageFunction in here
     *
     * @param $siteId
     * @param $pageName
     * @param $pageType
     * @param $pageRevision
     * @param $urlToPageFunction
     * @return mixed
     * @throws TrackingException
     */
    public function publishPageRevision($user, $siteId, $pageName, $pageType, $pageRevision, $urlToPageFunction)
    {
        if (!is_numeric($pageRevision)) {
            throw new InvalidArgumentException(
                'Invalid Page Revision Id . '
            );
        }

        if (empty($user)) {
            throw new TrackingException('A valid user is required in ' . get_class($this));
        }

        $siteId = $this->currentSite->getSiteId();

        $page = $this->pageRepo->publishPageRevision(
            $siteId,
            $pageName,
            $pageType,
            $pageRevision,
            $user->getId(),
            'Publish page in ' . get_class($this)
        );

        //@TODO change this to call publishFromExistingVersion() once we figure out how to get versionId
        $this->immuteblePageVersionRepo->publishFromNothing(
            new PageLocator($this->currentSite->getSiteId(),
                $this->rcmPageNameToPathname->__invoke($pageName, $pageType)),
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
     * @TODO stop passing $urlToPageFunction in here
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
                new PageLocator($this->currentSite->getSiteId(),
                    $this->rcmPageNameToPathname->__invoke($pageName, $pageType)),
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

//    /**
//     * getJsonResponse
//     *
//     * @param $data $data
//     *
//     * @return \Zend\Stdlib\ResponseInterface
//     */
//    public function getJsonResponse($data)
//    {
//        $view = new JsonModel();
//        $view->setTerminal(true);
//
//        $response = $this->getResponse();
//        $response->setContent(json_encode($data));
//
//        return $response;
//    }

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
