<?php
/**
 * Index Controller for the entire application
 *
 * This file contains the main controller used for the application.  This
 * should extend from the base class and should need no further modification.
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @author    Unkown <unknown@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 */
namespace Rcm\Controller;

use Rcm\Exception\PageNotFoundException;
use Rcm\Service\LayoutManager;
use Rcm\Service\PageManager;
use \Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Index Controller for the entire application
 *
 * This is main controller used for the application.  This should extend from
 * the base class located in Rcm and should need no further
 * modification.
 *
 * @category  Reliv
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Rel  iv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 *
 */
class IndexController extends AbstractActionController
{
    protected $pageManager;
    protected $layoutManager;
    protected $pageInfo;
    protected $notFound = false;

    public function __construct(
        PageManager $pageManager,
        LayoutManager $layoutManager
    ) {
        $this->pageManager = $pageManager;
        $this->layoutManager = $layoutManager;
    }

    protected function pageNotFound()
    {
        $this->notFound = true;

        try {
            $pageInfo = $this->pageManager->getPageRevisionInfo('n', 'not-found');
            $this->getResponse()->setStatusCode(404);
            return $pageInfo;
        } catch(PageNotFoundException $e) {
            $this->notFoundAction();
        }

        return null;
    }

    public function indexAction()
    {
        $pageName = $this->getEvent()->getRouteMatch()->getParam('page');
        $pageType = $this->getEvent()->getRouteMatch()->getParam('pageType');
        $pageRevisionId = $this->getEvent()->getRouteMatch()->getParam('revision', null);

        if (empty($pageName)) {
            $pageName = 'index';
        }

        if (empty($pageType)) {
            $pageType = 'n';
        }

        try {
            $pageInfo = $this->pageManager->getPageRevisionInfo($pageType, $pageName, $pageRevisionId);
        } catch(PageNotFoundException $e) {
            $pageInfo = $this->pageNotFound();
        }

        $this->pageInfo = $pageInfo;

        if (!empty($pageInfo['siteLayoutOverride'])) {
            $layoutView = $this->layout();
            $layoutTemplatePath = $this->layoutManager->getLayout($pageInfo['siteLayoutOverride']);
            $layoutView->setTemplate('layout/' . $layoutTemplatePath);
        }

        $viewModel = new ViewModel(array('pageInfo' => $pageInfo));
        $viewModel->setTemplate('pages/' . $this->layoutManager->getPageTemplate($pageInfo['pageLayout']));
        return $viewModel;
    }
}
