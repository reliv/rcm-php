<?php

namespace Rcm\Controller;

use Rcm\Entity\Page;
use Rcm\Entity\Site;
use Rcm\Renderer\PageRender;
use Zend\Http\Response;
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
    protected $pageRender;

    /**
     * Constructor.
     *
     * @param PageRender $pageRender
     * @param Site       $currentSite
     */
    public function __construct(
        PageRender $pageRender,
        Site $currentSite
    ) {
        $this->pageRender = $pageRender;
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
     * getPageRender
     *
     * @return PageRender
     */
    protected function getPageRender()
    {
        return $this->getServiceLocator()->get(PageRender::class);
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
        $pageRender = $this->getPageRender();

        $response = new \Rcm\Http\Response();
        $layoutView = $this->layout();
        $viewModel = new ViewModel();

        return $pageRender->renderByName(
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
