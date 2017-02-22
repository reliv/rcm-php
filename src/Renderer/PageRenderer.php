<?php

namespace Rcm\Renderer;

use Doctrine\ORM\EntityManager;
use Rcm\Acl\CmsPermissionChecks;
use Rcm\Entity\Page;
use Rcm\Entity\Site;
use Rcm\Http\Response;
use Rcm\Service\LayoutManager;
use Rcm\Service\PageRenderDataService;
use Rcm\Service\PageStatus;
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
     * @param EntityManager         $entityManager
     * @param LayoutManager         $layoutManager
     * @param CmsPermissionChecks   $cmsPermissionChecks
     * @param PageRenderDataService $pageRenderDataService
     * @param PageStatus            $pageStatus
     */
    public function __construct(
        EntityManager $entityManager,
        LayoutManager $layoutManager,
        CmsPermissionChecks $cmsPermissionChecks,
        PageRenderDataService $pageRenderDataService,
        PageStatus $pageStatus
    ) {
        $this->entityManager = $entityManager;
        $this->layoutManager = $layoutManager;
        $this->cmsPermissionChecks = $cmsPermissionChecks;
        $this->pageRenderDataService = $pageRenderDataService;
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
     * renderZf2
     *
     * @param Response       $response
     * @param ModelInterface $layoutView
     * @param ViewModel      $viewModel
     * @param Site           $site
     * @param Page           $page
     * @param null           $revisionId
     *
     * @return ViewModel|Response
     */
    public function renderZf2(
        Response $response,
        ModelInterface $layoutView,
        ViewModel $viewModel,
        Site $site,
        Page $page,
        $revisionId = null
    ) {
        $pageRenderData = $this->pageRenderDataService->getData(
            $site,
            $page,
            $revisionId
        );

        if (empty($pageRenderData->getPage())) {
            $response->setStatusCode($this->pageStatus->getNotFoundStatus());

            return $response;
        }

        $httpStatus = $pageRenderData->getHttpStatus();

        if ($httpStatus == $this->pageStatus->getNotAuthorizedStatus()) {
            $response->setStatusCode($this->pageStatus->getNotAuthorizedStatus());

            return $response;
        }

        $response->setStatusCode(
            $httpStatus
        );

        $site = $pageRenderData->getSite();
        $page = $pageRenderData->getPage();
        $requestedPageData = $pageRenderData->getRequestedPage();

        $layoutView = $this->prepareLayoutView(
            $layoutView,
            $site,
            $page
        );

        $layoutView->setVariable(
            'page',
            $page
        );

        $layoutView->setVariable(
            'site',
            $site
        );

        $layoutView->setVariable(
            'httpStatus',
            $httpStatus
        );

        $layoutView->setVariable(
            'requestedPageData',
            $requestedPageData
        );

        $viewModel->setVariable(
            'page',
            $page
        );
        $viewModel->setVariable(
            'httpStatus',
            $httpStatus
        );

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
     * renderZf2ByName
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
    public function renderZf2ByName(
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

        return $this->renderZf2(
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
}
