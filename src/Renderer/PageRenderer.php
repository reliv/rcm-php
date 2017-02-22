<?php

namespace Rcm\Renderer;

use Rcm\Entity\Page;
use Rcm\Entity\PageRenderData;
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
     * @var LayoutManager
     */
    protected $layoutManager;

    /**
     * @var PageStatus
     */
    protected $pageStatus;

    /**
     * Constructor.
     *
     * @param LayoutManager         $layoutManager
     * @param PageRenderDataService $pageRenderDataService
     * @param PageStatus            $pageStatus
     */
    public function __construct(
        LayoutManager $layoutManager,
        PageRenderDataService $pageRenderDataService,
        PageStatus $pageStatus
    ) {
        $this->layoutManager = $layoutManager;
        $this->pageRenderDataService = $pageRenderDataService;
        $this->pageStatus = $pageStatus;
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
     * renderZf2
     *
     * @param Response       $response
     * @param ModelInterface $layoutView
     * @param ViewModel      $viewModel
     * @param PageRenderData $pageRenderData
     *
     * @return Response|ViewModel
     */
    public function renderZf2(
        Response $response,
        ModelInterface $layoutView,
        ViewModel $viewModel,
        PageRenderData $pageRenderData
    ) {
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
        $pageRenderData = $this->pageRenderDataService->getData(
            $site,
            $pageName,
            $pageType,
            $revisionId
        );

        return $this->renderZf2(
            $response,
            $layoutView,
            $viewModel,
            $pageRenderData
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
}
