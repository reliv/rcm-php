<?php

namespace Rcm\Page\Renderer;

use Rcm\Entity\Page;
use Rcm\Entity\Site;
use Rcm\Http\Response;
use Rcm\Page\PageData\PageDataBc;
use Rcm\Page\PageData\PageDataService;
use Rcm\Page\PageStatus\PageStatus;
use Rcm\Page\PageTypes\PageTypes;
use Rcm\Service\LayoutManager;
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

    protected $viewRenderer;

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
        PhpRenderer $viewRenderer
    ) {
        $this->layoutManager = $layoutManager;
        $this->pageDataService = $pageDataService;
        $this->pageStatus = $pageStatus;
        $this->viewRenderer = $viewRenderer;
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

        return $viewModel;

        $layoutView->addChild($viewModel);

        $renderedHtml = $this->viewRenderer->render($layoutView);

        $response = new Response();
        $response->setStatusCode($httpStatus);
        $response->setContent($renderedHtml);

        return $response;
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
