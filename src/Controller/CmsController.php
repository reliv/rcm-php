<?php

namespace Rcm\Controller;

use Rcm\Entity\Page;
use Rcm\Entity\Revision;
use Rcm\Entity\Site;
use Rcm\Exception\PageNotFoundException;
use Rcm\Renderer\PageRender;
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
     * @var PageRender
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
     * getPageRender
     *
     * @return PageRender
     */
    protected function getPageRender()
    {
        return $this->getServiceLocator()->get(PageRender::class);
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
        $pageRender = $this->getPageRender();

        $response = new \Rcm\Http\Response();
        $layoutView = $this->layout();
        $viewModel = new ViewModel();

        return $pageRender->render(
            $response,
            $layoutView,
            $viewModel,
            $site,
            $page,
            $revisionId
        );
    }
}
