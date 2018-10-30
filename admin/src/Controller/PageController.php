<?php

namespace RcmAdmin\Controller;

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
use Rcm\ImmutableHistory\VersionRepository;
use Rcm\Repository\Page as PageRepo;
use Rcm\Tracking\Exception\TrackingException;
use RcmUser\Service\RcmUserService;
use RcmUser\User\Entity\UserInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Stdlib\ResponseInterface;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

/**
 * Admin Page Controller for the CMS
 *
 * This is Admin Page Controller for the CMS.  This should extend from
 * the base class and should need no further modification.
 *
 * @category  Reliv
 * @package   RcmAdmin
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 *
 * @method Response redirectToPage($pageName, $pageType) Redirect to CMS
 *                                                                  Page
 *
 * @method boolean rcmIsAllowed($resource, $action) Is User Allowed
 * @method string urlToPage($pageName, $pageType = 'n', $pageRevision = null) Get Url To a Page
 */
class PageController extends AbstractActionController
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

    /**
     * @param Site $currentSite
     * @param RcmUserService $rcmUserService
     * @param PageRepo $pageRepo
     */
    public function __construct(
        Site $currentSite,
        RcmUserService $rcmUserService,
        PageRepo $pageRepo,
        $revisionRepo,
        VersionRepository $immuteblePageVersionRepo,
        PageContentFactory $immutablePageContentFactory,
        RcmPageNameToPathname $rcmPageNameToPathname
    ) {
        $this->currentSite = $currentSite;
        $this->pageRepo = $pageRepo;
        $this->revisionRepo = $revisionRepo;
        $this->rcmUserService = $rcmUserService;
        $this->immuteblePageVersionRepo = $immuteblePageVersionRepo;
        $this->immutablePageContentFactory = $immutablePageContentFactory;
        $this->rcmPageNameToPathname = $rcmPageNameToPathname;

        $this->view = new ViewModel();
        $this->view->setTerminal(true);
    }

    /**
     * @return Response|ViewModel
     * @throws TrackingException
     */
    public function newAction()
    {
        /** @var ResourceName $resourceName */
        $resourceName = $this->getServiceLocator()->get(
            ResourceName::class
        );

        $resourceId = $resourceName->get(
            ResourceName::RESOURCE_SITES,
            $this->currentSite->getSiteId(),
            ResourceName::RESOURCE_PAGES
        );

        if (!$this->rcmUserService->isAllowed(
            $resourceId,
            'create'
        )
        ) {
            $response = new Response();
            $response->setStatusCode('401');

            return $response;
        }

        /** @var \RcmAdmin\Form\NewPageForm $form */
        $form = $this->getServiceLocator()
            ->get('FormElementManager')
            ->get(\RcmAdmin\Form\NewPageForm::class);

        /** @var \Zend\Http\Request $request */
        $request = $this->request;

        if ($request->isGet()) {
            $form->get('url')->setValue($request->getQuery('url'));
        }

        $data = $request->getPost();

        $form->setValidationGroup('url');
        $form->setData($data);

        $user = $this->rcmUserService->getCurrentUser();

        if (empty($user)) {
            throw new TrackingException('A valid user is required in ' . get_class($this));
        }

        if ($request->isPost() && $form->isValid()) {
            $validatedData = $form->getData();

            // Create a new page
            if (empty($validatedData['page-template'])
                && !empty($validatedData['main-layout'])
            ) {
                $pageData = [
                    'name' => $validatedData['url'],
                    'pageTitle' => $validatedData['title'],
                    'siteLayoutOverride' => $validatedData['main-layout'],
                    'createdByUserId' => $user->getId(),
                    'createdReason' => 'New page in ' . get_class($this),
                    'author' => $user->getName(),
                ];

                $this->pageRepo->createPage(
                    $this->currentSite,
                    $pageData
                );
            } elseif (!empty($validatedData['page-template'])) {
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
                    'pageType' => 'n',
                    'createdByUserId' => $user->getId(),
                    'createdReason' => 'New page from template in ' . get_class($this),
                    'author' => $user->getName(),
                ];

                $this->pageRepo->copyPage(
                    $this->currentSite,
                    $page,
                    $pageData
                );
            }

            $this->view->setVariable(
                'newPageUrl',
                $this->urlToPage(
                    $validatedData['url'],
                    'n'
                )
            );
            $this->view->setTemplate('rcm-admin/page/success');

            return $this->view;
        } elseif ($request->isPost() && !$form->isValid()) {
            $this->view->setVariable(
                'errors',
                $form->getMessages()
            );
        }

        $this->view->setVariable(
            'form',
            $form
        );

        return $this->view;
    }

    /**
     * createTemplateFromPageAction
     *
     * @return Response|ViewModel
     * @throws \Rcm\Exception\PageNotFoundException
     * @throws TrackingException
     */
    public function createTemplateFromPageAction()
    {
        /** @var ResourceName $resourceName */
        $resourceName = $this->getServiceLocator()->get(
            ResourceName::class
        );

        $resourceId = $resourceName->get(
            ResourceName::RESOURCE_SITES,
            $this->currentSite->getSiteId(),
            ResourceName::RESOURCE_PAGES
        );

        if (!$this->rcmUserService->isAllowed(
            $resourceId,
            'create'
        )
        ) {
            $response = new Response();
            $response->setStatusCode('401');

            return $response;
        }

        $sourcePage = $this->getEvent()
            ->getRouteMatch()
            ->getParam(
                'rcmPageName',
                'index'
            );

        $sourcePageRevision = $this->getEvent()
            ->getRouteMatch()
            ->getParam(
                'rcmPageRevision',
                null
            );

        $sourcePageType = $this->getEvent()
            ->getRouteMatch()
            ->getParam(
                'rcmPageType',
                'n'
            );

        /** @var \RcmAdmin\Form\CreateTemplateFromPageForm $form */
        $form = $this->getServiceLocator()
            ->get('FormElementManager')
            ->get(\RcmAdmin\Form\CreateTemplateFromPageForm::class);

        /** @var \Zend\Http\Request $request */
        $request = $this->request;

        $data = $request->getPost();

        $form->setValidationGroup('template-name');
        $form->setData($data);

        if ($request->isPost() && $form->isValid()) {
            $validatedData = $form->getData();

            $page = $this->pageRepo->getPageByName(
                $this->currentSite,
                $sourcePage,
                $sourcePageType
            );

            if (empty($page)) {
                throw new PageNotFoundException(
                    'Unable to locate source page to copy'
                );
            }

            $pageId = $page->getPageId();

            $user = $this->rcmUserService->getCurrentUser();

            if (empty($user)) {
                throw new TrackingException('A valid user is required in ' . get_class($this));
            }

            $pageData = [
                'createdByUserId' => $user->getId(),
                'createdReason' => 'New page in ' . get_class($this),
                'author' => $user->getName(),
                'name' => $validatedData['template-name'],
                'pageTitle' => null,
                'pageType' => 't',
            ];

            $this->pageRepo->copyPage(
                $this->currentSite,
                $page,
                $pageData,
                $sourcePageRevision
            );

            $this->view->setVariable(
                'newPageUrl',
                $this->urlToPage(
                    $validatedData['template-name'],
                    't'
                )
            );
            $this->view->setTemplate('rcm-admin/page/success');

            return $this->view;
        }

        $this->view->setVariable(
            'form',
            $form
        );
        $this->view->setVariable(
            'rcmPageName',
            $sourcePage
        );
        $this->view->setVariable(
            'rcmPageRevision',
            $sourcePageRevision
        );
        $this->view->setVariable(
            'rcmPageType',
            $sourcePageType
        );

        return $this->view;
    }

    /**
     * publishPageRevisionAction
     *
     * @return Response|\Zend\Http\Response
     * @throws \Rcm\Exception\InvalidArgumentException
     * @throws TrackingException
     */
    public function publishPageRevisionAction()
    {
        /** @var ResourceName $resourceName */
        $resourceName = $this->getServiceLocator()->get(
            ResourceName::class
        );

        $resourceId = $resourceName->get(
            ResourceName::RESOURCE_SITES,
            $this->currentSite->getSiteId(),
            ResourceName::RESOURCE_PAGES
        );

        if (!$this->rcmUserService->isAllowed(
            $resourceId,
            'create'
        )
        ) {
            $response = new Response();
            $response->setStatusCode('401');

            return $response;
        }

        $pageName = $this->getEvent()
            ->getRouteMatch()
            ->getParam(
                'rcmPageName',
                'index'
            );

        $pageRevision = $this->getEvent()
            ->getRouteMatch()
            ->getParam(
                'rcmPageRevision',
                null
            );

        $pageType = $this->getEvent()
            ->getRouteMatch()
            ->getParam(
                'rcmPageType',
                'n'
            );

        if (!is_numeric($pageRevision)) {
            throw new InvalidArgumentException(
                'Invalid Page Revision Id.'
            );
        }
        $user = $this->rcmUserService->getCurrentUser();

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

        return $this->redirect()->toUrl(
            $this->urlToPage(
                $pageName,
                $pageType
            )
        );
    }

    /**
     * savePageAction
     *
     * @return Response|ResponseInterface
     *
     * @throws TrackingException
     */
    public function savePageAction()
    {
        /** @var ResourceName $resourceName */
        $resourceName = $this->getServiceLocator()->get(
            ResourceName::class
        );

        $resourceId = $resourceName->get(
            ResourceName::RESOURCE_SITES,
            $this->currentSite->getSiteId(),
            ResourceName::RESOURCE_PAGES
        );

        if (!$this->rcmUserService->isAllowed(
            $resourceId,
            'edit'
        )
        ) {
            $response = new Response();
            $response->setStatusCode('401');

            return $response;
        }

        // @todo - might validate these against the data coming in
        $pageName = $this->getEvent()
            ->getRouteMatch()
            ->getParam(
                'rcmPageName',
                'index'
            );

        $pageRevision = $this->getEvent()
            ->getRouteMatch()
            ->getParam(
                'rcmPageRevision',
                null
            );

        $pageType = $this->getEvent()
            ->getRouteMatch()
            ->getParam(
                'rcmPageType',
                'n'
            );

        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();

        $user = $this->rcmUserService->getCurrentUser();

        if (empty($user)) {
            throw new TrackingException('A valid user is required in ' . get_class($this));
        }

        if ($request->isPost()) {
            /** @var \Zend\Stdlib\Parameters $data */
            $data = $request->getPost()->toArray();

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
                $return['redirect'] = $this->urlToPage(
                    $pageName,
                    $pageType,
                    $resultRevisionId
                );
            } else {
                $return['redirect'] = $this->urlToPage(
                    $pageName,
                    $pageType,
                    $pageRevision
                );
            }

            return $this->getJsonResponse($return);
        }

        $response = new Response();
        $response->setStatusCode('404');

        return $response;
    }

    /**
     * getJsonResponse
     *
     * @param $data $data
     *
     * @return \Zend\Stdlib\ResponseInterface
     */
    public function getJsonResponse($data)
    {
        $view = new JsonModel();
        $view->setTerminal(true);

        $response = $this->getResponse();
        $response->setContent(json_encode($data));

        return $response;
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
                'Save Data missing plugins.
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
    protected function cleanSaveData(&$data)
    {
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

    /**
     * @return RcmUserService
     */
    protected function getRcmUserService()
    {
        return $this->rcmUserService;
    }

    /**
     * @return UserInterface
     * @throws TrackingException
     */
    protected function getCurrentUser()
    {
        /** @var RcmUserService $service */
        $service = $this->getRcmUserService();

        $user = $service->getCurrentUser();

        if (empty($user)) {
            throw new TrackingException('A valid user is required in ' . get_class($this));
        }

        return $user;
    }
}
