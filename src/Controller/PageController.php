<?php

namespace RcmAdmin\Controller;

use Rcm\Entity\Site;
use Rcm\Exception\InvalidArgumentException;
use Rcm\Exception\PageNotFoundException;
use Rcm\Http\Response;
use Rcm\Repository\Page as PageRepo;
use Rcm\Tracking\Exception\TrackingException;
use RcmUser\Service\RcmUserService;
use RcmUser\User\Entity\User;
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

    /**
     * @param Site           $currentSite
     * @param RcmUserService $rcmUserService
     * @param PageRepo       $pageRepo
     */
    public function __construct(
        Site $currentSite,
        RcmUserService $rcmUserService,
        PageRepo $pageRepo
    ) {
        $this->currentSite = $currentSite;
        $this->pageRepo = $pageRepo;
        $this->rcmUserService = $rcmUserService;

        $this->view = new ViewModel();
        $this->view->setTerminal(true);
    }

    /**
     * @return Response|ViewModel
     * @throws TrackingException
     */
    public function newAction()
    {
        if (!$this->rcmIsAllowed(
            'sites.' . $this->currentSite->getSiteId() . '.pages',
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
            throw new TrackingException('A valid user is required in ' . self::class);
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
                    'createdReason' => 'New page in ' . self::class,
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
                    'createdReason' => 'New page from template in ' . self::class,
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
     */
    public function createTemplateFromPageAction()
    {
        if (!$this->rcmIsAllowed(
            'sites.' . $this->currentSite->getSiteId() . '.pages',
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
                throw new TrackingException('A valid user is required in ' . self::class);
            }

            $pageData = [
                'createdByUserId' => $user->getId(),
                'createdReason' => 'New page in ' . self::class,
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
     */
    public function publishPageRevisionAction()
    {
        if (!$this->rcmIsAllowed(
            'sites.' . $this->currentSite->getSiteId() . '.pages',
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

        $this->pageRepo->publishPageRevision(
            $this->currentSite->getSiteId(),
            $pageName,
            $pageType,
            $pageRevision
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
     */
    public function savePageAction()
    {
        if (!$this->rcmIsAllowed(
            'sites.' . $this->currentSite->getSiteId() . '.pages',
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
            throw new TrackingException('A valid user is required in ' . self::class);
        }

        if ($request->isPost()) {
            /** @var \Zend\Stdlib\Parameters $data */
            $data = $request->getPost()->toArray();

            $this->prepSaveData($data);

            $result = $this->pageRepo->savePage(
                $this->currentSite,
                $pageName,
                $pageRevision,
                $pageType,
                $data,
                $user->getId(),
                'Save existing page in ' . self::class,
                $user->getName()
            );

            if (empty($result)) {
                $return['redirect'] = $this->urlToPage(
                    $pageName,
                    $pageType,
                    $pageRevision
                );
            } else {
                $return['redirect'] = $this->urlToPage(
                    $pageName,
                    $pageType,
                    $result
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
     * @return User
     * @throws TrackingException
     */
    protected function getCurrentUser()
    {
        /** @var RcmUserService $service */
        $service = $this->getRcmUserService();

        $user = $service->getCurrentUser();

        if (empty($user)) {
            throw new TrackingException('A valid user is required in ' . self::class);
        }

        return $user;
    }
}
