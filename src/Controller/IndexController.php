<?php

namespace Rcm\Controller;

use Rcm\Entity\Site;
use Rcm\Renderer\PageRenderer;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Class IndexController
 * @deprecated  PLEASE USE CmsController Instead
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
     * @param PageRenderer $pageRenderer
     * @param Site       $currentSite
     */
    public function __construct(
        PageRenderer $pageRenderer,
        Site $currentSite
    ) {
        $this->pageRenderer = $pageRenderer;
        $this->currentSite = $currentSite;
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
            ->getParam('pageType', 'n');

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
     * getPageRenderer
     *
     * @return PageRenderer
     */
    protected function getPageRenderer()
    {
        return $this->getServiceLocator()->get(PageRenderer::class);
    }

    /**
     * getCmsResponse
     *
     * @param Site   $site
     * @param        $pageName
     * @param string $pageType
     * @param null   $revisionId
     *
     * @return \Rcm\Http\Response|ViewModel
     */
    public function getCmsResponse(
        Site $site,
        $pageName,
        $pageType = 'n',
        $revisionId = null
    ) {
        $pageRenderer = $this->getPageRenderer();

        $response = new \Rcm\Http\Response();
        $layoutView = $this->layout();
        $viewModel = new ViewModel();

        return $pageRenderer->renderZf2ByName(
            $response,
            $layoutView,
            $viewModel,
            $site,
            $pageName,
            $pageType,
            $revisionId
        );
    }
}
