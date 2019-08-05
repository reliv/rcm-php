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


    /**
     * Constructor.
     *
     * @param PageRendererBc $pageRenderer
     * @param Site $currentSite
     */
    public function __construct(
        PageRendererBc $pageRenderer,
        Site $currentSite
    ) {
        $this->pageRenderer = $pageRenderer;
        $this->currentSite = $currentSite;
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

        return $result;
    }
}
