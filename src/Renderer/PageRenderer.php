<?php

namespace Rcm\Renderer;

use Doctrine\ORM\EntityManager;
use Rcm\Acl\CmsPermissionChecks;
use Rcm\Entity\Page;
use Rcm\Entity\Site;
use Rcm\Exception\PageNotFoundException;
use Rcm\Http\Response;
use Rcm\Service\LayoutManager;
use Zend\View\Model\ModelInterface;
use Zend\View\Model\ViewModel;

/**
 * Class PageRenderer
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class PageRenderer
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var LayoutManager
     */
    protected $layoutManager;

    /**
     * @var CmsPermissionChecks
     */
    protected $cmsPermissionChecks;

    /**
     * @var PageStatus
     */
    protected $pageStatus;

    /**
     * Constructor.
     *
     * @param EntityManager       $entityManager
     * @param LayoutManager       $layoutManager
     * @param CmsPermissionChecks $cmsPermissionChecks
     * @param PageStatus          $pageStatus
     */
    public function __construct(
        EntityManager $entityManager,
        LayoutManager $layoutManager,
        CmsPermissionChecks $cmsPermissionChecks,
        PageStatus $pageStatus
    ) {
        $this->entityManager = $entityManager;
        $this->layoutManager = $layoutManager;
        $this->cmsPermissionChecks = $cmsPermissionChecks;
        $this->pageStatus = $pageStatus;
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
     * getLayoutManager
     *
     * @return LayoutManager
     */
    protected function getLayoutManager()
    {
        return $this->layoutManager;
    }

    /**
     * getPageRepository
     *
     * @return \Rcm\Repository\Page
     */
    protected function getPageRepository()
    {
        $entityManager = $this->getEntityManager();

        return $entityManager->getRepository(Page::class);
    }

    /**
     * render
     *
     * @param Response       $response
     * @param ModelInterface $layoutView
     * @param ViewModel      $viewModel
     * @param Site           $site
     * @param Page           $page
     * @param null           $revisionId
     *
     * @return ViewModel
     */
    public function render(
        Response $response,
        ModelInterface $layoutView,
        ViewModel $viewModel,
        Site $site,
        Page $page,
        $revisionId = null
    ) {
        $requestPageName = $page->getName();
        $requestPageType = $page->getPageType();

        $page = $this->preparePage(
            $site,
            $page,
            $revisionId
        );

        if (empty($page)) {
            $response = $this->responseWithNotFound($response);
            $response->setStatusCode($this->pageStatus->getNotFoundStatus());

            return $response;
        }

        $allowed = $this->cmsPermissionChecks->isPageAllowedForReading($page);

        if (!$allowed) {
            $response = $this->responseWithUnauthorized($response);
            $response->setStatusCode($this->pageStatus->getNotAuthorizedStatus());

            return $response;
        }

        $httpStatus = $this->getStatus($requestPageName, $page->getName());

        $response->setStatusCode(
            $httpStatus
        );

        $layoutView = $this->prepareLayoutView(
            $layoutView,
            $site,
            $page
        );

        $layoutView->setVariable('page', $page);
        $layoutView->setVariable('site', $site);
        $layoutView->setVariable('httpStatus', $httpStatus);

        /* This is for client, so it can tell if the rendered page is not the requested page */
        $requestedPageData = [
            'name' => strtolower($requestPageName),
            'type' => strtolower($requestPageType),
            'revision' => $revisionId,
        ];
        $layoutView->setVariable('requestedPageData', $requestedPageData);

        $viewModel->setVariable('page', $page);
        $viewModel->setVariable('httpStatus', $httpStatus);

        $viewModel->setTemplate(
            'pages/'
            . $this->getLayoutManager()->getSitePageTemplate(
                $site,
                $page->getPageLayout()
            )
        );

        return $viewModel;
    }

    /**
     * renderByName
     *
     * @param Response       $response
     * @param ModelInterface $layoutView
     * @param ViewModel      $viewModel
     * @param Site           $site
     * @param string         $pageName
     * @param string         $pageType
     * @param null           $revisionId
     *
     * @return Response|ViewModel
     */
    public function renderByName(
        Response $response,
        ModelInterface $layoutView,
        ViewModel $viewModel,
        Site $site,
        $pageName,
        $pageType = 'n',
        $revisionId = null
    ) {
        $page = $this->getPage(
            $site,
            $pageName,
            $pageType
        );

        return $this->render(
            $response,
            $layoutView,
            $viewModel,
            $site,
            $page,
            $revisionId
        );
    }

    /**
     * prepareLayoutView
     *
     * @param ModelInterface $layoutView
     * @param Site           $site
     * @param Page           $page
     *
     * @return ModelInterface
     */
    protected function prepareLayoutView(
        ModelInterface $layoutView,
        Site $site,
        Page $page
    ) {
        $layoutOverRide = $page->getSiteLayoutOverride();

        if (!empty($layoutOverRide)) {
            $layoutTemplatePath = $this->getLayoutManager()->getSiteLayout(
                $site,
                $layoutOverRide
            );

            $layoutView->setTemplate('layout/' . $layoutTemplatePath);
        }

        return $layoutView;
    }

    /**
     * getStatus
     *
     * @param string $requestPageName
     * @param string $responsePageName
     *
     * @return int
     */
    protected function getStatus($requestPageName, $responsePageName)
    {
        if (!$this->isRequestedPage($requestPageName, $responsePageName)) {
            return $this->pageStatus->getStatus($responsePageName);
        }

        return $this->pageStatus->getOkStatus();
    }

    /**
     * isRequestedPage
     *
     * @param $requestPageName
     * @param $responsePageName
     *
     * @return bool
     */
    protected function isRequestedPage($requestPageName, $responsePageName)
    {
        return ($requestPageName == $responsePageName);
    }

    /**
     * getRevision
     *
     * @param Site $site
     * @param Page $page
     * @param null $pageRevisionId
     *
     * @return null|\Rcm\Entity\Revision
     */
    protected function getRevision(
        Site $site,
        Page $page,
        $pageRevisionId = null
    ) {
        $hasRevisionId = !empty($pageRevisionId);

        $hasAccess = false;

        if ($hasRevisionId) {
            $hasAccess = $this->cmsPermissionChecks->shouldShowRevisions(
                $site->getSiteId(),
                $page->getName(),
                $page->getPageType()
            );
        }

        if ($hasAccess) {
            return $page->getRevisionById($pageRevisionId);
        }

        // Check for staging
        $hasAccess = $this->cmsPermissionChecks->siteAdminCheck(
            $site
        );

        $revision = null;

        if ($hasAccess) {
            // @todo is this right, This means admins can never see published revisions
            $revision = $page->getStagedRevision();
        }

        if (!empty($revision)) {
            return $revision;
        }

        // Finally look for published revision
        return $page->getPublishedRevision();
    }

    /**
     * getPage
     *
     * @param Site $site
     * @param      $pageName
     * @param      $type
     *
     * @return null|Page
     */
    protected function getPage(
        Site $site,
        $pageName,
        $type
    ) {
        if (empty($site) || !$site->getSiteId()) {
            return null;
        }

        /** @var \Rcm\Repository\Page $pageRepo */
        $pageRepo = $this->getPageRepository();

        /* Get the Page for display */
        $page = $pageRepo->getPageByName(
            $site,
            $pageName,
            $type
        );

        return $page;
    }

    /**
     * preparePage
     *
     * @param Site      $site
     * @param Page|null $page
     * @param int|null  $revisionId
     *
     * @return null|Page
     */
    protected function preparePage(
        Site $site,
        $page,
        $revisionId = null
    ) {
        if (empty($page)) {
            $page = $this->getNotFoundPage($site);
        }

        $revision = $this->getRevision($site, $page, $revisionId);

        if (empty($revision)) {
            $revision = $page->getCurrentRevision();
        }

        if (empty($revision)) {
            $page = $this->getNotFoundPage($site);
            $revision = $page->getCurrentRevision();//$this->getRevision($site, $page);
        }

        if (empty($page)) {
            return null;
        }

        if (empty($revision)) {
            throw new PageNotFoundException(
                'No revision found for page name: ' .
                json_encode($page->getName()) . ' id: ' .
                json_encode($page->getPageId()) . ' type: ' .
                json_encode($page->getPageType())
            );
        }

        $page->setCurrentRevision($revision);

        return $page;
    }

    /**
     * getNotFoundPage
     *
     * @param Site $site
     *
     * @return mixed
     */
    protected function getNotFoundPage(
        Site $site
    ) {
        /** @var \Rcm\Repository\Page $pageRepo */
        $pageRepo = $this->getPageRepository();

        $page = $pageRepo->getPageByName(
            $site,
            $site->getNotFoundPage(),
            'n'
        );

        if (empty($page)) {
            throw new PageNotFoundException(
                'No default page defined for 404 not found error'
            );
        }

        return $page;
    }

    /**
     * responseWithUnauthorized
     *
     * @param Response $response
     *
     * @return Response
     */
    protected function responseWithUnauthorized(Response $response)
    {
        $response->setStatusCode(
            $this->pageStatus->getNotAuthorizedStatus()
        );

        return $response;
    }

    /**
     * responseWithNotFound
     *
     * @param Response $response
     *
     * @return Response
     */
    protected function responseWithNotFound(Response $response)
    {
        $response->setStatusCode(
            $this->pageStatus->getNotFoundStatus()
        );

        return $response;
    }
}
