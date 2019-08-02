<?php

namespace Rcm\Page\Renderer;

use Rcm\Entity\Page;
use Rcm\Entity\Site;
use Rcm\Http\Response;
use Rcm\Page\PageData\PageDataBc;
use Rcm\Page\PageData\PageDataService;
use Rcm\Page\PageStatus\PageStatus;
use Rcm\Page\PageTypes\PageTypes;
use Rcm\Renderer\RenderViewModelWithChildren;
use Rcm\Service\LayoutManager;
use RcmAdmin\Controller\AdminPanelController;
use Zend\Expressive\ZendView\ZendViewRenderer;
use Zend\View\Model\ModelInterface;
use Zend\View\Model\ViewModel;
use Zend\View\Renderer\PhpRenderer;

/**
 * Class PageRenderer
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class PageRendererBc
{
    /**
     * @var LayoutManager
     */
    protected $layoutManager;

    /**
     * @var PageStatus
     */
    protected $pageStatus;

    protected $renderViewModelWithChildren;

    /**
     * Constructor.
     *
     * @param LayoutManager $layoutManager
     * @param PageDataService $pageDataService
     * @param PageStatus $pageStatus
     */
    public function __construct(
        LayoutManager $layoutManager,
        PageDataService $pageDataService,
        PageStatus $pageStatus,
        RenderViewModelWithChildren $renderViewModelWithChildren
    ) {
        $this->layoutManager = $layoutManager;
        $this->pageDataService = $pageDataService;
        $this->pageStatus = $pageStatus;
        $this->renderViewModelWithChildren=$renderViewModelWithChildren;
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
     * @param Response $response
     * @param ModelInterface $layoutView
     * @param ViewModel $viewModel
     * @param PageDataBc $pageData
     *
     * @return Response|ViewModel
     */
    public function renderZf2(
        Response $response,
        ModelInterface $layoutView,
        ViewModel $viewModel,
        PageDataBc $pageData
    ) {
        if (empty($pageData->getPage())) {
            $response->setStatusCode($this->pageStatus->getNotFoundStatus());

            return $response;
        }

        $httpStatus = $pageData->getHttpStatus();

        if ($httpStatus == $this->pageStatus->getNotAuthorizedStatus()) {
            $response->setStatusCode($this->pageStatus->getNotAuthorizedStatus());

            return $response;
        }

        $response->setStatusCode(
            $httpStatus
        );

        $site = $pageData->getSite();
        $page = $pageData->getPage();
        $requestedPageData = $pageData->getRequestedPage();

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
        $viewModel->setVariable(
            'useInstanceConfig',
            true
        );

        $viewModel->setTemplate(
            'pages/'
            . $this->getLayoutManager()->getSitePageTemplate(
                $site,
                $page->getPageLayout()
            )
        );

        /**
         * Make sure the response has status code 404 if we are 404ing
         */
        if ($httpStatus === 404) {
            $layoutView->addChild($viewModel);
            $renderedHtml = $this->renderViewModelWithChildren->__invoke($layoutView);
            $response = new Response();
            $response->setStatusCode($viewModel->getVariable('httpStatus'));
            $response->setContent($renderedHtml);

            return $response;
        }

        return $viewModel;
    }


    /**
     * renderZf2ByName
     *
     * @param Response $response
     * @param ModelInterface $layoutView
     * @param ViewModel $viewModel
     * @param Site $site
     * @param string $pageName
     * @param string $pageType
     * @param null $revisionId
     *
     * @return Response|ViewModel
     */
    public function renderZf2ByName(
        Response $response,
        ModelInterface $layoutView,
        ViewModel $viewModel,
        Site $site,
        $pageName,
        $pageType = PageTypes::NORMAL,
        $revisionId = null
    ) {
        $pageData = $this->pageDataService->getData(
            $site,
            $pageName,
            $pageType,
            $revisionId
        );

        return $this->renderZf2(
            $response,
            $layoutView,
            $viewModel,
            $pageData
        );
    }

    /**
     * prepareLayoutView
     *
     * @param ModelInterface $layoutView
     * @param Site $site
     * @param Page $page
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
