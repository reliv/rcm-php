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
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://github.com/reliv
 */

namespace Rcm\Controller;

use Rcm\Exception\ContainerNotFoundException;
use Rcm\Service\LayoutManager;
use Rcm\Service\PageManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Http\Response;

/**
 * Index Controller for the entire application
 *
 * This is main controller used for the application.  This should extend from
 * the base class located in Rcm and should need no further
 * modification.
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 *
 * @method Response redirectToPage($pageName, $pageType) Redirect to CMS
 *                                                                  Page
 *
 * @method boolean rcmUserIsAllowed($resource, $action, $providerId) Is User Allowed
 */
class IndexController extends AbstractActionController
{
    /** @var string */
    public $pageName;

    /** @var string */
    public $pageType;

    /** @var integer */
    public $pageRevisionId;

    /** @var integer  */
    protected $siteId;

    /** @var \Rcm\Service\PageManager  */
    protected $pageManager;

    /** @var \Rcm\Service\LayoutManager  */
    protected $layoutManager;


    protected $pageInfo;
    protected $notFound = false;

    /**
     * Constructor
     *
     * @param PageManager   $pageManager   Page Manager needed to get current page.
     * @param LayoutManager $layoutManager Layout Manager to handle themes
     * @param integer       $siteId        Current Site Id
     */
    public function __construct(
        PageManager $pageManager,
        LayoutManager $layoutManager,
        $siteId
    ) {
        $this->pageManager   = $pageManager;
        $this->layoutManager = $layoutManager;
        $this->siteId        = $siteId;
    }

    /**
     * Look for page titled not-found.  If can't find a CMS page by that name
     * throw a generic 404 and let Zend take care of the error handling.
     *
     * @return mixed|null
     */
    protected function pageNotFound()
    {
        $this->notFound = true;

        try {
            $this->pageName = 'not-found';
            $this->pageType = 'n';
            $this->pageRevisionId = null;

            $pageInfo = $this->pageManager->getRevisionInfo('not-found');

            /** @var \Zend\Http\Response $response */
            $response =$this->getResponse();
            $response->setStatusCode(404);

            return $pageInfo;
        } catch(ContainerNotFoundException $e) {
            return $this->notFoundAction();
        }
    }

    /**
     * Index Action.  Main action for page in the CMS.
     *
     * @return ViewModel
     */
    public function indexAction()
    {
        $this->pageName = $this->getEvent()
            ->getRouteMatch()
            ->getParam('page', 'index');

        $this->pageType = $this->getEvent()
            ->getRouteMatch()
            ->getParam('pageType', 'n');

        $this->pageRevisionId = $this->getEvent()
            ->getRouteMatch()
            ->getParam('revision', null);


        $userCanSeeRevisions = $this->shouldShowRevisions();

        if (!$userCanSeeRevisions && $this->pageRevisionId) {
            return $this->redirectToPage($this->pageName, $this->pageType);
        }

        try {
            $pageInfo = $this->pageManager->getRevisionInfo(
                $this->pageName,
                $this->pageRevisionId,
                $this->pageType,
                $userCanSeeRevisions
            );
        } catch(ContainerNotFoundException $e) {
            $pageInfo = $this->pageNotFound();

            if ($pageInfo instanceof ViewModel) {
                return $pageInfo;
            }
        }

        $this->pageInfo = $pageInfo;

        if (!empty($pageInfo['siteLayoutOverride'])) {
            $layoutView = $this->layout();

            $layoutTemplatePath = $this->layoutManager->getLayout(
                $pageInfo['siteLayoutOverride']
            );

            $layoutView->setTemplate('layout/' . $layoutTemplatePath);
        }

        $viewModel = new ViewModel(array('pageInfo' => $pageInfo));

        $viewModel->setTemplate(
            'pages/'
            .$this->layoutManager->getPageTemplate($pageInfo['pageLayout'])
        );

        return $viewModel;
    }


    /**
     * Check to make sure user can see revisions
     *
     * @return bool
     */
    protected function shouldShowRevisions()
    {
        $allowedRevisions = $this->rcmUserIsAllowed(
            'sites.'.$this->siteId.'.pages.'.$this->pageName,
            'edit',
            'Rcm\Acl\ResourceProvider'
        );

        if ($allowedRevisions) {
            return true;
        }

        $allowedRevisions = $this->rcmUserIsAllowed(
            'sites.'.$this->siteId.'.pages.'.$this->pageName,
            'approve',
            'Rcm\Acl\ResourceProvider'
        );

        if ($allowedRevisions) {
            return true;
        }

        $allowedRevisions = $this->rcmUserIsAllowed(
            'sites.'.$this->siteId.'.pages.'.$this->pageName,
            'revisions',
            'Rcm\Acl\ResourceProvider'
        );

        if ($allowedRevisions) {
            return true;
        }

        return false;
    }
}
