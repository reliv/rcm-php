<?php

namespace Rcm\Controller;

use Rcm\Entity\Page;
use Rcm\Entity\Revision;
use Rcm\Entity\Site;
use Rcm\Exception\PageNotFoundException;
use Rcm\Renderer\PageRenderer;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Class CmsController
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class CmsController extends AbstractActionController
{
    /**
     * @var \Rcm\Entity\Site
     */
    protected $currentSite;

    /**
     * @var PageRenderer
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
     * getPageRenderer
     *
     * @return PageRenderer
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
        $page = $this->getEvent()
            ->getRouteMatch()
            ->getParam('page');

        $revision = $this->getEvent()
            ->getRouteMatch()
            ->getParam('revision', null);

        return $this->getCmsResponse(
            $this->currentSite,
            $page,
            $revision
        );
    }

    /**
     * getCmsResponse
     *
     * @param Site $site
     * @param Page $page
     * @param null $revisionId
     *
     * @return \Rcm\Http\Response|ViewModel
     */
    public function getCmsResponse(
        Site $site,
        Page $page,
        $revisionId = null
    ) {
        $pageRenderer = $this->getPageRenderer();

        $response = new \Rcm\Http\Response();
        $layoutView = $this->layout();
        $viewModel = new ViewModel();

        return $pageRenderer->renderZf2(
            $response,
            $layoutView,
            $viewModel,
            $site,
            $page,
            $revisionId
        );
    }
}
