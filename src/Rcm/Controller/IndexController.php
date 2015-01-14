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

use Rcm\Entity\Page;
use Rcm\Entity\Revision;
use Rcm\Entity\Site;
use Rcm\Exception\PageNotFoundException;
use Rcm\Repository\Page as PageRepo;
use Rcm\Service\LayoutManager;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

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
 * @method boolean rcmIsAllowed($resource, $action) Is User Allowed
 * @method boolean shouldShowRevisions($siteId, $pageName, $pageType = 'n') Should Show Revisions for pages
 * @method boolean rcmIsSiteAdmin() Is user a CMS admin
 * @method boolean rcmIsPageAllowed(Page $page) Is user allowed to view a page
 */
class IndexController extends AbstractActionController
{
    /** @var string */
    public $pageName;

    /** @var string */
    public $pageType;

    /** @var integer */
    public $pageRevisionId;

    /** @var \Rcm\Entity\Site */
    protected $currentSite;

    /** @var integer */
    protected $siteId;

    /** @var \Rcm\Service\LayoutManager */
    protected $layoutManager;

    /** @var  \Rcm\Repository\Page */
    protected $pageRepo;

    protected $pageInfo;
    protected $notFound = false;

    /**
     * Constructor
     *
     * @param LayoutManager $layoutManager Layout Manager to get layouts.
     * @param Site          $currentSite   Current Site Entity
     * @param PageRepo      $pageRepo      Rcm Page Repository
     */
    public function __construct(
        LayoutManager $layoutManager,
        Site $currentSite,
        PageRepo $pageRepo
    ) {
        $this->layoutManager = $layoutManager;
        $this->currentSite = $currentSite;
        $this->pageRepo = $pageRepo;
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

        return $this->getCmsResponse(
            $this->currentSite,
            $this->pageName,
            $this->pageType,
            $this->pageRevisionId
        );
    }

    public function getCmsResponse(
        Site $site,
        $pageName,
        $pageType = 'n',
        $revisionId = null
    ) {

        /* Get the Page for display */
        $page = $this->pageRepo->getPageByName(
            $site,
            $pageName,
            $pageType
        );

        if (!$page) {
            $page = $this->renderNotFoundPage($site);
        }

        $allowed = $this->rcmIsPageAllowed($page);

        if (!$allowed) {
            return $this->getUnauthorizedResponse();
        }

        $this->prepPageRevisionForDisplay($page, $revisionId);

        if (!empty($revisionId) && !$page->getCurrentRevision()) {
            return $this->redirectToPage(
                $page->getName(),
                $page->getPageType()
            );
        }

        // if we have no revision, page is not found
        if (!$page->getCurrentRevision()) {
            $page = $this->renderNotFoundPage($site);
        }

        $this->prepLayoutView($site, $page, $page->getSiteLayoutOverride());

        $viewModel = new ViewModel(['page' => $page]);

        $viewModel->setTemplate(
            'pages/'
            . $this->layoutManager->getSitePageTemplate(
                $site,
                $page->getPageLayout()
            )
        );

        return $viewModel;
    }

    public function renderNotFoundPage($site)
    {
        $this->pageName = $site->getNotFoundPage();
        $this->pageType = 'n';
        $this->pageRevisionId = null;

        $page = $this->pageRepo->getPageByName(
            $site,
            $site->getNotFoundPage(),
            'n'
        );

        if (empty($page)) {
            throw new PageNotFoundException('No default page defined for 404 not found error');
            // return $this->notFoundAction();
        }

        $response = $this->getResponse();
        $response->setStatusCode(410);

        return $page;
    }

    protected function prepLayoutView(Site $site, Page $page, $layoutOverRide)
    {
        /** @var ViewModel $layoutView */
        $layoutView = $this->layout();

        if (!empty($layoutOverRide)) {
            $layoutTemplatePath = $this->layoutManager->getSiteLayout(
                $page->getSite(),
                $layoutOverRide
            );

            $layoutView->setTemplate('layout/' . $layoutTemplatePath);
        }

        if ($this->pageInfo['currentRevisionId']
            != $this->pageInfo['revision']['revisionId']
        ) {
            $layoutView->setVariable('rcmDraft', true);
        }

        $layoutView->setVariable('page', $page);
        $layoutView->setVariable('site', $site);
    }

    public function prepPageRevisionForDisplay(
        Page $page,
        $pageRevisionId = null
    ) {
        //  First Check for a page Revision
        if (!empty($pageRevisionId)) {
            $userCanSeeRevisions = $this->shouldShowRevisions(
                $page->getSite()->getSiteId(),
                $page->getName(),
                $page->getPageType()
            );

            if ($userCanSeeRevisions) {
                $revision = $page->getRevisionById($pageRevisionId);

                if (!empty($revision) || $revision instanceof Revision) {
                    $page->setCurrentRevision($revision);
                }

                return;
            }
        }

        // Check for staging
        if ($this->rcmIsSiteAdmin($page->getSite())) {
            $revision = $page->getStagedRevision();

            if (!empty($revision) || $revision instanceof Revision) {
                $page->setCurrentRevision($revision);
                return;
            }
        }

        // Finally look for published revision
        $revision = $page->getPublishedRevision();
        if (!empty($revision) || $revision instanceof Revision) {
            $page->setCurrentRevision($revision);
        }

        return;
    }

    protected function getUnauthorizedResponse()
    {
        $response = new \Rcm\Http\Response();
        $response->setStatusCode(401);
        return $response;
    }
}
