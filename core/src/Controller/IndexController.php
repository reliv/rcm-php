<?php

namespace Rcm\Controller;

use Rcm\Entity\Site;
use Rcm\Exception\RuntimeException;
use Rcm\Page\PageTypes\PageTypes;
use Rcm\Page\Renderer\PageRendererBc;
use Rcm\Renderer\RenderViewModelWithChildren;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Renderer\PhpRenderer;

/**
 * Class IndexController
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class IndexController extends AbstractActionController
{
    /**
     * @var \Rcm\Entity\Site
     */
    protected $currentSite;

    /**
     * @var \Rcm\Service\LayoutManager
     */
    protected $pageRenderer;

    protected $renderViewModelWithChildren;

    /**
     * Constructor.
     *
     * @param PageRendererBc $pageRenderer
     * @param Site $currentSite
     */
    public function __construct(
        PageRendererBc $pageRenderer,
        Site $currentSite,
        RenderViewModelWithChildren $renderViewModelWithChildren
    ) {
        $this->pageRenderer = $pageRenderer;
        $this->currentSite = $currentSite;
        $this->renderViewModelWithChildren = $renderViewModelWithChildren;
    }

    /**
     * getPageRenderer
     *
     * @return PageRendererBc
     */
    protected function getPageRenderer()
    {
        return $this->pageRenderer;
    }

    /**
     * Index Action.  Main action for page in the CMS.
     *
     * @return ViewModel
     */
    public function indexAction()
    {
        $pageName = $this->getEvent()
            ->getRouteMatch()
            ->getParam('page', 'index');

        $pageType = $this->getEvent()
            ->getRouteMatch()
            ->getParam('pageType', PageTypes::NORMAL);

        $pageRevisionId = $this->getEvent()
            ->getRouteMatch()
            ->getParam('revision', null);

        return $this->getCmsResponse(
            $this->currentSite,
            $pageName,
            $pageType,
            $pageRevisionId
        );
    }

    /**
     * getCmsResponse
     *
     * @param Site $site
     * @param        $pageName
     * @param string $pageType
     * @param null $revisionId
     *
     * @return \Rcm\Http\Response|ViewModel
     */
    public function getCmsResponse(
        Site $site,
        $pageName,
        $pageType = PageTypes::NORMAL,
        $revisionId = null
    ) {
        $pageRenderer = $this->getPageRenderer();

        $response = new \Rcm\Http\Response();
        $layoutView = $this->layout();
        $viewModel = new ViewModel();

        $result = $pageRenderer->renderZf2ByName(
            $response,
            $layoutView,
            $viewModel,
            $site,
            $pageName,
            $pageType,
            $revisionId
        );

        /**
         * This has the following goals:
         * 1) Have not-found pages return HTTP status code 404
         * 2) Don't break the admin menu
         * 3) Don't break product detail pages
         *
         * In order to accomplish these goals, this code returns a response if 404ing
         * but returns the "content child view" otherwise. This is because other
         * older parts of the system expect a "child content view" to be returned here
         * so they can manipulate it for custom features.
         */
        if ($result instanceof ViewModel) {
            if ($result->getVariable('httpStatus') === 404) {
                $renderedHtml = $this->renderViewModelWithChildren->__invoke($result);
                $response = new Response();
                $response->setStatusCode($result->getVariable('httpStatus'));
                $response->setContent($renderedHtml);

                return $response;
            } else {
                $contentView = $result->getChildrenByCaptureTo('content')[0];

                return $contentView;
            }
        }

        return $result;
    }
}
