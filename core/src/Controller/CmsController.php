<?php
//
//namespace Rcm\Controller;
//
//use Rcm\Entity\Page;
//use Rcm\Entity\Site;
//use Rcm\Page\Renderer\PageRendererBc;
//use Zend\Mvc\Controller\AbstractActionController;
//use Zend\View\Model\ViewModel;
//
///**
// * Class CmsController
// *
// * @author    James Jervis
// * @license   License.txt
// * @link      https://github.com/jerv13
// */
//class CmsController extends AbstractActionController
//{
//    /**
//     * @var \Rcm\Entity\Site
//     */
//    protected $currentSite;
//
//    /**
//     * @var PageRendererBc
//     */
//    protected $pageRenderer;
//
//    /**
//     * Constructor.
//     *
//     * @param PageRendererBc $pageRenderer
//     * @param Site           $currentSite
//     */
//    public function __construct(
//        PageRendererBc $pageRenderer,
//        Site $currentSite
//    ) {
//        $this->pageRenderer = $pageRenderer;
//        $this->currentSite = $currentSite;
//    }
//
//    /**
//     * getPageRenderer
//     *
//     * @return PageRendererBc
//     */
//    protected function getPageRenderer()
//    {
//        return $this->pageRenderer;
//    }
//
//    /**
//     * Index Action.  Main action for page in the CMS.
//     *
//     * @return ViewModel
//     */
//    public function indexAction()
//    {
//        $page = $this->getEvent()
//            ->getRouteMatch()
//            ->getParam('page');
//
//        $revision = $this->getEvent()
//            ->getRouteMatch()
//            ->getParam('revision', null);
//
//        return $this->getCmsResponse(
//            $this->currentSite,
//            $page,
//            $revision
//        );
//    }
//
//    /**
//     * getCmsResponse
//     *
//     * @param Site $site
//     * @param Page $page
//     * @param null $revisionId
//     *
//     * @return \Rcm\Http\Response|ViewModel
//     */
//    public function getCmsResponse(
//        Site $site,
//        Page $page,
//        $revisionId = null
//    ) {
//        $pageRenderer = $this->getPageRenderer();
//
//        $response = new \Rcm\Http\Response();
//        $layoutView = $this->layout();
//        $viewModel = new ViewModel();
//
//        return $pageRenderer->renderZf2ByName(
//            $response,
//            $layoutView,
//            $viewModel,
//            $site,
//            $this->getEvent(),
//            $page->getName(),
//            $page->getPageType(),
//            $revisionId
//        );
//    }
//}
