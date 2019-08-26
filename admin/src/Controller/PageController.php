<?php

namespace RcmAdmin\Controller;

use Psr\Container\ContainerInterface;
use Rcm\Acl\NotAllowedException;
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
use Rcm\Page\PageTypes\PageTypes;
use Rcm\Repository\Page as PageRepo;
use Rcm\Tracking\Exception\TrackingException;
use Rcm\Http\NotAllowedResponseJsonZf2;
use Rcm\SecureRepo\PageSecureRepo;
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

    protected $immuteblePageVersionRepo;

    protected $revisionRepo;

    /**
     * @param Site $currentSite
     * @param RcmUserService $rcmUserService
     * @param PageRepo $pageRepo
     */
    public function __construct(
        PageSecureRepo $pageSecureRepo,
        Site $currentSite
    ) {
        $this->pageSecureRepo = $pageSecureRepo;
        $this->currentSite = $currentSite;

        $this->view = new ViewModel();
        $this->view->setTerminal(true);
    }

    /**
     * @return Response|ViewModel
     * @throws TrackingException
     */
    public function newAction()
    {
        /** @oldControllerAclAccessCheckReplacedWithDeeperSecureRepoCheck */


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

        if ($request->isPost() && $form->isValid()) {
            $validatedData = $form->getData();
            $validatedData['name'] = $validatedData['url'];
            $validatedData['pageTitle'] = $validatedData['title'];
            $validatedData['siteId'] = $this->currentSite->getSiteId();
            $validatedData['pageType'] = PageTypes::NORMAL;

            // Create a new page
            if (empty($validatedData['page-template'])
                && !empty($validatedData['main-layout'])
            ) {
                $validatedData['siteLayoutOverride'] = $validatedData['main-layout'];
                try {
                    $this->pageSecureRepo->createNewPage(
                        $this->currentSite->getSiteId(),
                        $validatedData['name'],
                        $validatedData['pageType'],
                        $validatedData
                    );
                } catch (NotAllowedException $e) {
                    return new NotAllowedResponseJsonZf2();
                }
            } elseif (!empty($validatedData['page-template'])) {
                try {
                    $this->pageSecureRepo->createNewPageFromTemplate(
                        $validatedData
                    );
                } catch (NotAllowedException $e) {
                    return new NotAllowedResponseJsonZf2();
                }
            } else {
                throw new \Exception('Could not figure out creation method from request properties');
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
     * publishPageRevisionAction
     *
     * @return Response|\Zend\Http\Response
     * @throws \Rcm\Exception\InvalidArgumentException
     * @throws TrackingException
     */
    public function publishPageRevisionAction()
    {
        $request = $this->getRequest();

//        //HTTP method check //this was comented our because the current UI bizarly does an HTTP GET to this action
//        if (!$request->isPost()) {
//
//            $response = new Response();
//            $response->setStatusCode('405');
//
//            return $response;
//        }

        /** @oldControllerAclAccessCheckReplacedWithDeeperSecureRepoCheck */

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

        try {
            $page = $this->pageSecureRepo->publishPageRevision(
                $this->currentSite->getSiteId(),
                $pageName,
                $pageType,
                $pageRevision
            );
        } catch (NotAllowedException $e) {
            return new NotAllowedResponseJsonZf2();
        }
        $newUrl = $this->urlToPage($page->getName(), $page->getPageType(), $pageRevision);

        return $this->redirect()->toUrl($newUrl);
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
        $request = $this->getRequest();

        //HTTP method check
        if (!$request->isPost()) {
            $response = new Response();
            $response->setStatusCode('405');

            return $response;
        }

        /** @oldControllerAclAccessCheckReplacedWithDeeperSecureRepoCheck */

        // @todo - might validate these against the data coming in
        $pageName = $this->getEvent()
            ->getRouteMatch()
            ->getParam(
                'rcmPageName',
                'index'
            );

        $originalRevisionId = $this->getEvent()
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

        //Note: This should probably come out of the ZF2 request instead but that didn't seem to work
        $data = json_decode(file_get_contents('php://input'), true);

        try {
            $responseData = $this->pageSecureRepo->savePageDraft(
                $pageName,
                $pageType,
                $data,
                function ($pageName, $pageType = 'n', $pageRevision = null) {
                    return $this->urlToPage($pageName, $pageType, $pageRevision);
                },
                $originalRevisionId
            );
        } catch (NotAllowedException $e) {
            return new NotAllowedResponseJsonZf2();
        }

        return $this->getJsonResponse($responseData);
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
}
